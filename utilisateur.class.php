<?php
// Importation de la classe BD pour gérer la connexion à la base de données
require_once 'bd.class.php';

/**
 * Classe Utilisateur
 *
 * Cette classe représente un utilisateur de l'application et fournit des méthodes
 * pour gérer l'inscription et l'authentification. Dans cette version, les mots
 * de passe sont cryptés avec `base64_encode()` et décryptés avec `base64_decode()`.
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
     * Le mot de passe est crypté avant d'être inséré en base.
     *
     * @throws PDOException En cas d'erreur lors de l'exécution de la requête.
     */
    public function inscription(): void
    {
        // Obtention de l'instance PDO via la classe BD
        $baseDeDonnees = BD::getInstancePdo();

        // Cryptage du mot de passe avant l'insertion en base
        $passwordCrypte = base64_encode($this->password);

        // Préparation de la requête d'insertion
        $requete = $baseDeDonnees->prepare(
            'INSERT INTO utilisateurs (email, password) VALUES (:email, :password)'
        );

        // Exécution de la requête avec les données de l'utilisateur
        $requete->execute([
            'email' => $this->email,
            'password' => $passwordCrypte
        ]);
    }

    /**
     * Authentifie un utilisateur.
     *
     * Cette méthode tente de récupérer un utilisateur en base à partir de son email
     * et compare le mot de passe en clair fourni par l'utilisateur
     * avec le mot de passe stocké en base, décrypté à l'aide de `base64_decode`.
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
           $donneesUtilisateurEnBase['password'] : le mot de passe crypté et stocké en base pour l'utilisateur */
        $donneesUtilisateurEnBase = $requete->fetch(PDO::FETCH_ASSOC);

        // Vérifie si l'utilisateur en base existe
        if ($donneesUtilisateurEnBase)
        {
            // Décryptage du mot de passe stocké en base pour comparaison
            $passwordDecrypte = base64_decode($donneesUtilisateurEnBase['password']);

            if ($passwordDecrypte === $this->getPassword())
            {
                // Synchronise l'identifiant récupéré de la base de données avec l'objet courant.
                // Cette synchronisation permettra d'utiliser l'identifiant dans d'autres fonctionnalités
                // (comme la mémorisation de l'utilisateur connecté en session ou la gestion des droits).
                $this->identifiant = $donneesUtilisateurEnBase['identifiant'];

                // Authentification réussie
                return true;
            }
        }

        // Authentification échouée
        return false;
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
