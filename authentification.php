<?php

// Import de la classe Utilisateur pour gérer l'authentification
require_once 'utilisateur.class.php';

// Démarrage de la session pour gérer l'état de connexion
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    // Récupération des données du formulaire
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Création d'un objet Utilisateur avec les données saisies
    $utilisateur = new Utilisateur($email, $password);

    try
    {
        // Tentative d'authentification
        if ($utilisateur->authentification())
        {
            /**
             * Mise en session des informations de l'utilisateur connecté.
             * 
             * Ici, nous choisissons de mémoriser uniquement les attributs essentiels
             * de l'utilisateur sous forme de tableau associatif plutôt que de stocker
             * l'objet `Utilisateur` dans sa globalité. 
             * 
             * **Avantages de cette approche :**
             * 1. **Mémoire réduite :** Seuls les attributs nécessaires sont conservés,
             *    évitant de stocker des méthodes ou des données inutiles.
             * 
             * 2. **Sécurité accrue :** En stockant uniquement les informations strictement nécessaires,
             *    on limite les risques associés à une fuite accidentelle des données sensibles
             *    (par exemple, des méthodes ou attributs non pertinents dans un objet complet).
             * 
             * **Inconvénient :**
             * - Moins conforme au paradigme objet, nécessitant parfois des accès explicites
             *   à la base pour obtenir d'autres données ou exécuter des méthodes.
             */
            $_SESSION['authentifie'] = true; // Indique que l'utilisateur est authentifié
            $_SESSION['utilisateur'] = [
                'id' => $utilisateur->getIdentifiant(),

                // Ci-dessous l'email est nettoyé avec htmlspecialchars pour éviter les failles XSS (Cross-Site Scripting).
                // Une faille XSS permettrait à un utilisateur malveillant d'injecter du code JavaScript dans une page,
                // ce qui pourrait compromettre la sécurité de l'application et des utilisateurs.
                // Le concept des failles XSS sera abordé en détail dans un prochain tutoriel.
                'email' => htmlspecialchars($utilisateur->getEmail()),
                'role' => $utilisateur->getRole() // Ajout du rôle
            ];

            // Redirection vers la page d'administration après authentification réussie
            header('Location: admin.php');
            exit;
        }
        else
        {
            // Message d'erreur en cas de combinaison email/mot de passe incorrecte
            echo "<h1>Authentification échouée</h1>";
            echo "<p>Email ou mot de passe incorrect.</p>";
            echo '<a href="authentification.html">Retourner au formulaire d\'authentification</a>';
        }
    }
    catch (Exception $e)
    {
        // Gestion de l'exception "compte_desactive"
        if ($e->getMessage() === "compte_desactive")
        {
            // Calcul du temps restant avant réactivation du compte
            $tempsRestant = $utilisateur->tempsRestantAvantReactivationCompte();
            $minutes = floor($tempsRestant / 60);
            $secondes = $tempsRestant % 60;

            // Message informant l'utilisateur que son compte est temporairement bloqué
            echo "<h1>Compte bloqué</h1>";
            echo "<p>Votre compte est temporairement désactivé en raison de plusieurs tentatives échouées.
                  Veuillez réessayer dans {$minutes} minutes et {$secondes} secondes.</p>";
            echo '<a href="authentification.html">Retourner au formulaire d\'authentification</a>';
        }
        else
        {
            // Gestion des autres exceptions
            echo "<h1>Erreur inattendue</h1>";
            echo "<p>{$e->getMessage()}</p>";
            echo '<a href="authentification.html">Retourner au formulaire d\'authentification</a>';
        }
    }
}
