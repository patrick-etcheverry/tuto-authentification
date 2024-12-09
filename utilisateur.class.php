<?php
// Importation de la classe BD pour gérer la connexion à la base de données
require_once 'bd.class.php';

/**
 * Classe Utilisateur
 *
 * Cette classe représente un utilisateur de l'application et gère l'inscription,
 * l'authentification et la protection contre les attaques par force brute.
 */
class Utilisateur
{
    private ?int $identifiant = null;                  // Identifiant unique de l'utilisateur en BD
    private string $email;                            // Adresse email de l'utilisateur
    private string $password;                         // Mot de passe de l'utilisateur
    private int $tentativesEchouees = 0;              // Nombre de tentatives échouées
    private ?string $dateDernierEchecConnexion = null; // Date et heure du dernier échec de connexion
    private string $statutCompte = 'actif';           // Statut du compte (actif ou désactivé)
    private string $role = 'user';                    // Rôle de l'utilisateur (admin ou user). Par défaut user.

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

        // Obtention de l'instance PDO
        $baseDeDonnees = BD::getInstancePdo();

        // Hachage du mot de passe
        $passwordHache = password_hash($this->password, PASSWORD_BCRYPT);

        // Préparation de la requête d'insertion
        $requete = $baseDeDonnees->prepare(
            'INSERT INTO utilisateurs (email, password, role) VALUES (:email, :password, :role)'
        );

        // Exécution de la requête
        $requete->execute([
            'email' => $this->email,
            'password' => $passwordHache,
            'role' => $this->role // Par défaut, rôle "user"
        ]);
    }

    /**
     * Authentifie un utilisateur.
     *
     * Gère les tentatives échouées, désactive le compte après plusieurs échecs,
     * et lève une exception si le compte est désactivé.
     *
     * @return bool true si l'authentification réussit, false sinon.
     * @throws Exception Si le compte est désactivé.
     */
    public function authentification(): bool
    {
        // Connexion à la base de données
        $baseDeDonnees = BD::getInstancePdo();

        // Recherche de l'utilisateur
        $requete = $baseDeDonnees->prepare(
            'SELECT identifiant, password, tentatives_echouees, date_dernier_echec_connexion, statut_compte, role 
             FROM utilisateurs WHERE email = :email'
        );
        $requete->execute(['email' => $this->email]);
        $utilisateur = $requete->fetch(PDO::FETCH_ASSOC);

        // Si l'utilisateur n'existe pas, refuser la connexion
        if (!$utilisateur)
        {
            return false;
        }

        // Hydratation de l'objet avec les données de l'utilisateur
        $this->identifiant = $utilisateur['identifiant'];
        $this->tentativesEchouees = (int) $utilisateur['tentatives_echouees'];
        $this->dateDernierEchecConnexion = $utilisateur['date_dernier_echec_connexion'];
        $this->statutCompte = $utilisateur['statut_compte'];
        $this->role = $utilisateur['role'];

        // Vérification du statut du compte
        if ($this->statutCompte === 'desactive')
        {
            if (!$this->delaiAttenteEstEcoule())
            {
                // Lever une exception si le délai d'attente n'est pas écoulé
                throw new Exception("compte_desactive");
            }
            $this->reactiverCompte(); // Réactiver le compte si le délai est écoulé
        }

        // Vérification du mot de passe
        if (password_verify($this->password, $utilisateur['password']))
        {
            // Réinitialiser les tentatives échouées si nécessaire
            if ($this->tentativesEchouees > 0)
            {
                $this->reinitialiserTentativesConnexions();
            }
            return true; // Authentification réussie
        }
        else
        {
            $this->gererEchecConnexion(); // Gérer un échec de connexion
            return false; // Authentification échouée
        }
    }

    /**
     * Réinitialise les tentatives échouées après une authentification réussie.
     */
    private function reinitialiserTentativesConnexions(): void
    {
        $this->tentativesEchouees = 0;
        $this->dateDernierEchecConnexion = null;

        // Mise à jour en BD
        $baseDeDonnees = BD::getInstancePdo();
        $requete = $baseDeDonnees->prepare(
            'UPDATE utilisateurs SET tentatives_echouees = 0, date_dernier_echec_connexion = NULL WHERE identifiant = :id'
        );
        $requete->execute(['id' => $this->identifiant]);
    }

    /**
     * Gère un échec d'authentification.
     *
     * Incrémente le nombre de tentatives échouées et désactive le compte si nécessaire.
     */
    private function gererEchecConnexion(): void
    {
        $this->tentativesEchouees++;
        $baseDeDonnees = BD::getInstancePdo();

        if ($this->tentativesEchouees >= MAX_CONNEXIONS_ECHOUEES)
        {
            $requete = $baseDeDonnees->prepare(
                'UPDATE utilisateurs 
                 SET tentatives_echouees = :tentatives, date_dernier_echec_connexion = NOW(), statut_compte = "desactive" 
                 WHERE identifiant = :id'
            );
            $this->statutCompte = 'desactive';
        }
        else
        {
            $requete = $baseDeDonnees->prepare(
                'UPDATE utilisateurs 
                 SET tentatives_echouees = :tentatives, date_dernier_echec_connexion = NOW() 
                 WHERE identifiant = :id'
            );
        }

        $requete->execute([
            'tentatives' => $this->tentativesEchouees,
            'id' => $this->identifiant,
        ]);
    }

    /**
     * Réactive un compte désactivé si le délai d'attente est écoulé.
     */
    private function reactiverCompte(): void
    {
        $this->tentativesEchouees = 0;
        $this->dateDernierEchecConnexion = null;
        $this->statutCompte = 'actif';

        $baseDeDonnees = BD::getInstancePdo();
        $requete = $baseDeDonnees->prepare(
            'UPDATE utilisateurs 
             SET tentatives_echouees = 0, date_dernier_echec_connexion = NULL, statut_compte = "actif" 
             WHERE identifiant = :id'
        );
        $requete->execute(['id' => $this->identifiant]);
    }

    /**
     * Vérifie si le délai d'attente est écoulé pour réactiver le compte.
     *
     * @return bool true si le délai est écoulé, false sinon.
     */
    private function delaiAttenteEstEcoule(): bool
    {
        return $this->tempsRestantAvantReactivationCompte() === 0;
    }

    /**
     * Calcule le temps restant avant que le compte soit réactivé.
     *
     * @return int Temps restant en secondes. Retourne 0 si le délai est écoulé.
     */
    public function tempsRestantAvantReactivationCompte(): int
    {
        if (!$this->dateDernierEchecConnexion)
        {
            return 0;
        }

        $dernierEchecTimestamp = strtotime($this->dateDernierEchecConnexion);
        $tempsEcoule = time() - $dernierEchecTimestamp;

        return max(0, DELAI_ATTENTE_CONNEXION - $tempsEcoule);
    }

    /**
     * Génère et enregistre un token pour la réinitialisation de mot de passe.
     *
     * Le token est une chaîne aléatoire et unique, accompagnée d'une date d'expiration.
     * 
     * 1. Le token est généré à l'aide de `random_bytes` pour garantir un niveau élevé d'aléa et de sécurité.
     * 2. Une date d'expiration est associée au token pour limiter son utilisation dans le temps.
     * 
     * Note importante : le token doit être transmis de manière sécurisée (via HTTPS)
     * et utilisé uniquement pour valider une opération de réinitialisation de mot de passe.
     * 
     * @return string Le token généré.
     * @throws Exception Si l'utilisateur n'existe pas en base.
     */
    public function genererTokenReinitialisation(): string
    {
        // Connexion à la base de données
        $baseDeDonnees = BD::getInstancePdo();

        // Vérification de l'existence de l'utilisateur
        $requete = $baseDeDonnees->prepare('SELECT identifiant FROM utilisateurs WHERE email = :email');
        $requete->execute(['email' => $this->email]);
        $utilisateur = $requete->fetch(PDO::FETCH_ASSOC);

        if (!$utilisateur)
        {
            throw new Exception("Utilisateur introuvable");
        }

        // Génération d'un token unique
        $token = bin2hex(random_bytes(32));

        // Le token sera valide pendant 1 heure à partir de sa génération :
        $dateExpiration = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Mémorisation du token et de sa date d'expiration en BD
        $requete = $baseDeDonnees->prepare(
            'UPDATE utilisateurs SET token_reinitialisation = :token, expiration_token = :expiration 
             WHERE email = :email'
        );
        $requete->execute([
            'token' => $token,
            'expiration' => $dateExpiration,
            'email' => $this->email,
        ]);

        return $token;
    }


    /**
     * Getter pour l'identifiant.
     *
     * @return ?int Identifiant de l'utilisateur ou null s'il n'est pas défini.
     */
    public function getIdentifiant(): ?int
    {
        return $this->identifiant;
    }

    /**
     * Getter pour l'email.
     *
     * @return string Adresse email de l'utilisateur.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Getter pour le rôle.
     *
     * @return string Rôle de l'utilisateur.
     */
    public function getRole(): string
    {
        return $this->role;
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
