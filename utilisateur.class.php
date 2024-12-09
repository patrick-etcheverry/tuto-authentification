<?php
// Importation de la classe BD pour gérer la connexion à la base de données
require_once 'bd.class.php';

/**
 * Classe Utilisateur
 *
 * Cette classe représente un utilisateur de l'application et fournit des méthodes
 * pour gérer l'inscription et l'authentification. Dans cette version, les mots
 * de passe sont protégés à l'aide des fonctions `password_hash()` et `password_verify()`,
 * qui incluent automatiquement le salage et utilisent un algorithme sécurisé.
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
     *
     * - Utilise `password_hash()` pour hacher le mot de passe.
     * - Cette fonction génère automatiquement un sel unique pour chaque mot de passe,
     *   ce qui garantit qu'un même mot de passe produira un hachage différent.
     * - L'algorithme utilisé par défaut est `PASSWORD_BCRYPT`, considéré comme sécurisé.
     * - Le hachage inclut des métadonnées sur l'algorithme et le sel utilisé, permettant
     *   de vérifier correctement les mots de passe avec `password_verify()`.
     *
     * @throws PDOException En cas d'erreur lors de l'exécution de la requête.
     */
    public function inscription(): void
    {
        /* Hachage du mot de passe avec `password_hash`, qui inclut un sel unique automatiquement.
           Le paramètre `PASSWORD_BCRYPT` spécifie l'utilisation de l'algorithme Bcrypt.
           - Bcrypt est un algorithme de hachage robuste conçu pour protéger les mots de passe.
           - Il inclut un mécanisme de salage automatique pour rendre chaque hachage unique, 
             même pour des mots de passe identiques.
           - Il peut également être paramétré pour être "lent" à calculer, ce qui complique les attaques 
             par force brute. */
        $passwordHache = password_hash($this->password, PASSWORD_BCRYPT);

        // Connexion à la base de données
        $baseDeDonnees = BD::getInstancePdo();

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
     * et compare le mot de passe haché stocké en base avec celui fourni dans le formulaire,
     * en utilisant `password_verify()`.
     *
     * Étapes :
     * 1. L'objet courant (désigné par `$this`) est initialisé avec les données
     *    saisies dans le formulaire (email et mot de passe). Cela permet de représenter
     *    un utilisateur temporaire qui cherche à se connecter.
     * 2. La méthode recherche en base de données un utilisateur correspondant
     *    à l'email fourni.
     * 3. Si un utilisateur correspondant est trouvé, la méthode utilise `password_verify()`
     *    pour comparer le mot de passe fourni avec celui stocké en base.
     * 4. Si les mots de passe correspondent, l'objet courant est mis à jour avec
     *    l'identifiant de l'utilisateur en base, et la méthode retourne `true`.
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
        $requete->execute(['email' => $this->getEmail()]);

        /* Récupération des informations de l'utilisateur en base de données
           On récupère ici un tableau associatif contenant les champs et valeurs de la BD : 
           $donneesUtilisateurEnBase['identifiant'] : l'identifiant unique de l'utilisateur
           $donneesUtilisateurEnBase['password'] : le mot de passe haché stocké en base */
        $donneesUtilisateurEnBase = $requete->fetch(PDO::FETCH_ASSOC);

        // Vérifie si l'utilisateur en base existe
        if ($donneesUtilisateurEnBase)
        {
            // Vérification du mot de passe avec `password_verify()`
            if (password_verify($this->password, $donneesUtilisateurEnBase['password']))
            {
                // Synchronise l'identifiant récupéré de la base de données avec l'objet courant.
                // Cette synchronisation permettra d'utiliser l'identifiant dans d'autres fonctionnalités
                // (comme la mémorisation de l'utilisateur connecté en session ou la gestion des droits).
                $this->identifiant = $donneesUtilisateurEnBase['identifiant'];

                // Réinitialisation du mot de passe pour éviter de conserver des données sensibles
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
    // À partir de cette version, nous supprimons le getter pour le mot de passe afin d'adopter une gestion conforme 
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
