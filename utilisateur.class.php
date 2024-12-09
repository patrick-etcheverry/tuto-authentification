<?php
// Importation de la classe BD pour gérer la connexion à la base de données
require_once 'bd.class.php';

/**
 * Classe Utilisateur
 *
 * Cette classe représente un utilisateur de l'application et fournit des méthodes
 * pour gérer l'inscription et l'authentification.
 * Cette version introduit un contrôle de la robustesse des mots de passe.
 */
class Utilisateur
{
    private ?int $identifiant = null; // Identifiant unique de l'utilisateur en BD
    private string $email;           // Adresse email de l'utilisateur
    private string $password;        // Mot de passe en clair fourni lors de la création

    /**
     * Constructeur
     *
     * Le constructeur ne prend en paramètre que l'email et le mot de passe
     * car on construit l'utilisateur au moment de l'authentification (qu'elle
     * soit réussie ou non) et à ce stade on ne dispose que de ces deux
     * informations.
     * 
     * @param string $email Adresse email de l'utilisateur
     * @param string $password Mot de passe de l'utilisateur
     */
    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Vérifie si un email existe déjà en base.
     *
     * @return bool true si l'email existe, false sinon.
     */
    public function emailExiste(): bool
    {
        // Connexion à la base de données
        $baseDeDonnees = BD::getInstancePdo();

        // Préparation de la requête pour vérifier si l'email existe
        $requete = $baseDeDonnees->prepare(
            'SELECT COUNT(*) FROM utilisateurs WHERE email = :email'
        );

        // Exécution de la requête avec l'email récupéré au niveau du formulaire
        $requete->execute(['email' => $this->email]);

        // Retourne vrai si un utilisateur avec cet email existe, faux sinon
        return $requete->fetchColumn() > 0;
    }

    /**
     * Vérifie si un mot de passe est robuste.
     *
     * Critères de robustesse :
     * - Longueur minimale de 8 caractères.
     * - Contient au moins une lettre majuscule (A-Z).
     * - Contient au moins une lettre minuscule (a-z).
     * - Contient au moins un chiffre (0-9).
     * - Contient au moins un caractère spécial (@$!%*?&).
     *
     * @param string $password Le mot de passe à valider.
     * @return bool true si le mot de passe respecte les critères, false sinon.
     */
    public function estRobuste(string $password): bool
    {
        $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';

        // La fonction preg_match retourne 1 si une correspondance est trouvée.
        return preg_match($regex, $password) === 1;
    }

    /**
     * Inscription
     *
     * Enregistre un nouvel utilisateur dans la base de données.
     *
     * - Vérifie si l'email est déjà utilisé en base via `emailExiste()`.
     *   Si l'email existe, une exception avec l'identifiant `compte_existant` est levée.
     * 
     * - Vérifie si le mot de passe est suffisamment robuste via `estRobuste()`.
     *   Si ce n'est pas le cas, une exception avec l'identifiant `mdp_faible` est levée.
     * 
     * - Le mot de passe est haché avec `password_hash()` (qui inclut un sel aléatoire automatiquement)
     *   avant d'être inséré en base de données.
     *
     * @throws Exception Si l'email existe déjà en base ou si le mot de passe est invalide.
     * @throws PDOException En cas d'erreur lors de l'exécution de la requête.
     */
    public function inscription(): void
    {
        // Vérifie si le mot de passe est robuste
        if (!$this->estRobuste($this->password))
        {
            throw new Exception("mdp_faible");
        }

        // Vérifie si l'email existe déjà
        if ($this->emailExiste())
        {
            throw new Exception("compte_existant");
        }

        // Obtention de l'instance PDO via la classe BD
        $baseDeDonnees = BD::getInstancePdo();

        // Hachage du mot de passe avec password_hash()
        $passwordHache = password_hash($this->password, PASSWORD_BCRYPT);

        // Préparation de la requête d'insertion
        $requete = $baseDeDonnees->prepare(
            'INSERT INTO utilisateurs (email, password) VALUES (:email, :password)'
        );

        // Exécution de la requête avec les données de l'utilisateur
        $requete->execute([
            'email' => $this->email,
            'password' => $passwordHache
        ]);
    }

    /**
     * Authentifie un utilisateur.
     *
     * Cette méthode tente de récupérer un utilisateur en base à partir de son email
     * et compare le mot de passe fourni avec celui haché en base via `password_verify()`.
     *
     * @return bool true si l'authentification réussit, false sinon.
     */
    public function authentification(): bool
    {
        // Connexion à la base de données
        $baseDeDonnees = BD::getInstancePdo();

        // Recherche de l'utilisateur correspondant à l'email fourni
        $requete = $baseDeDonnees->prepare(
            'SELECT identifiant, password FROM utilisateurs WHERE email = :email'
        );

        // Exécution de la requête avec l'email de l'utilisateur
        $requete->execute(['email' => $this->email]);

        // Récupération des informations de l'utilisateur en base
        $utilisateurEnBase = $requete->fetch();

        // Vérifie si l'utilisateur en base existe
        if ($utilisateurEnBase)
        {
            // Utilise password_verify() pour comparer le mot de passe saisi et celui en base
            if (password_verify($this->password, $utilisateurEnBase['password']))
            {
                $this->identifiant = $utilisateurEnBase['identifiant'];

                // Réinitialisation du mot de passe en clair pour ne pas conserver de données sensibles
                $this->password = '';

                return true; // Authentification réussie
            }
        }

        return false; // Authentification échouée
    }

    /**
     * Getter pour l'identifiant
     *
     * @return ?int Identifiant de l'utilisateur ou null s'il n'est pas défini.
     */
    public function getIdentifiant(): ?int
    {
        return $this->identifiant;
    }

    /**
     * Getter pour l'email
     *
     * @return string Adresse email de l'utilisateur.
     */
    public function getEmail(): string
    {
        return $this->email;
    }


    /**
     * Getter pour le mot de passe
     */
    // Nous avons supprimé le getter pour le mot de passe afin d'adopter une gestion conforme 
    // aux standards actuels de sécurité. Cette suppression repose sur plusieurs principes clés :
    // 
    // 1. Respect des bonnes pratiques : Une fois le mot de passe haché, l'application n'a plus besoin de stocker 
    //    ni de manipuler le mot de passe en clair. Toutes les opérations de vérification utilisent uniquement 
    //    la version hachée du mot de passe.
    // 
    // 2. Réduction de la surface d'attaque : En supprimant le getter, on empêche toute partie du code ou un attaquant 
    //    exploitant une faille d'accéder au mot de passe en clair. Cela réduit le risque de compromission en cas 
    //    de fuite ou d'erreur de programmation.
    // 
    // 3. Approche "zéro connaissance" : Le mot de passe original reste exclusivement connu de l'utilisateur. 
    //    L'application ne le conserve jamais après sa vérification initiale, ce qui protège les utilisateurs même 
    //    en cas de faille ou de vol de données.
}
