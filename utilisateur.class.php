<?php
// Importation de la classe BD pour gérer la connexion à la base de données
require_once 'bd.class.php';

/**
 * Classe Utilisateur
 *
 * Cette classe représente un utilisateur de l'application et fournit des méthodes
 * pour gérer l'inscription et l'authentification. Dans cette version, les mots
 * de passe sont hachés avec `md5()` et salés manuellement pour chaque utilisateur.
 */
class Utilisateur
{
    private ?int $identifiant = null; // Identifiant unique de l'utilisateur en BD
    private string $email;           // Adresse email de l'utilisateur
    private string $password;        // Mot de passe de l'utilisateur

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
     * Inscription
     *
     * Enregistre un nouvel utilisateur dans la base de données.
     * Le mot de passe est haché avec un sel généré au moment de l'inscription,
     * garantissant une entropie élevée pour renforcer la sécurité.
     *
     * Notion d'entropie :
     * - L'entropie mesure le degré d'imprévisibilité d'une donnée.
     * - Plus l'entropie est élevée, plus il est difficile pour un attaquant de deviner ou de
     *   prédire la valeur de cette donnée.
     * - Avec 128 bits (2^128 combinaisons possibles), il est presque impossible 
     *   pour un attaquant de deviner un sel par hasard ou d'utiliser une table préconstruite 
     *   (table arc-en-ciel).
     *
     * @throws PDOException En cas d'erreur lors de l'exécution de la requête.
     */
    public function inscription(): void
    {
        // Génération d'un sel unique pour cet utilisateur
        $salt = bin2hex(random_bytes(16)); // 128 bits (16 octets) convertis en chaîne hexadécimale

        // Hachage du mot de passe avec le sel
        $passwordHache = hash('md5', $salt . $this->password);

        // Connexion à la base de données
        $baseDeDonnees = BD::getInstancePdo();

        // Préparation de la requête d'insertion
        $requete = $baseDeDonnees->prepare(
            'INSERT INTO utilisateurs (email, password, salt) VALUES (:email, :password, :salt)'
        );

        // Exécution de la requête avec les données de l'utilisateur
        $requete->execute([
            'email' => $this->email,
            'password' => $passwordHache,
            'salt' => $salt
        ]);
    }

    /**
     * Authentifie un utilisateur.
     *
     * Cette méthode tente de récupérer un utilisateur en base à partir de son email
     * et compare le mot de passe haché (avec salage) calculé à partir du mot de passe 
     * saisi dans le formulaire avec celui enregistré en base.
     *
     * @return bool true si l'authentification réussit, false sinon.
     */
    public function authentification(): bool
    {
        // Connexion à la base de données
        $baseDeDonnees = BD::getInstancePdo();

        // Recherche de l'utilisateur correspondant à l'email fourni
        $requete = $baseDeDonnees->prepare(
            'SELECT identifiant, password, salt FROM utilisateurs WHERE email = :email'
        );

        // Exécution de la requête avec l'email de l'utilisateur
        $requete->execute(['email' => $this->getEmail()]);

        /* Récupération des informations de l'utilisateur en base de données
           On récupère ici un tableau associatif contenant les champs et valeurs de la BD : 
           $donneesUtilisateurEnBase['identifiant'] : l'identifiant unique de l'utilisateur
           $donneesUtilisateurEnBase['password'] : le mot de passe haché stocké en base
           $donneesUtilisateurEnBase['salt'] : le sel unique stocké en base */
        $donneesUtilisateurEnBase = $requete->fetch(PDO::FETCH_ASSOC);

        // Vérifie si l'utilisateur en base existe
        if ($donneesUtilisateurEnBase)
        {
            // Récupération du sel qui avait été attribué à l'inscription de cet utilisateur 
            $salt = $donneesUtilisateurEnBase['salt'];

            // hachage et salage du mot de passe fourni dans le formulaire
            $passwordHache = hash('md5', $salt . $this->getPassword());

            // Comparaison du mot de passe saisi, haché et salé avec celui enregistré en base
            if ($passwordHache === $donneesUtilisateurEnBase['password'])
            {
                // Synchronise l'identifiant récupéré de la base de données avec l'objet courant.
                // Cette synchronisation permettra d'utiliser l'identifiant dans d'autres fonctionnalités
                // (comme la mémorisation de l'utilisateur connecté en session ou la gestion des droits).
                $this->identifiant = $donneesUtilisateurEnBase['identifiant'];
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
     *
     * @return string Mot de passe de l'utilisateur.
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
