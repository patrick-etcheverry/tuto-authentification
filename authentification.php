<?php

require_once 'utilisateur.class.php'; // Import de la classe Utilisateur

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    // Récupération des données du formulaire
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Création de l'utilisateur avec les données saisies
    $utilisateur = new Utilisateur($email, $password);

    try
    {
        // Tentative d'authentification
        if ($utilisateur->authentification())
        {
            echo "Authentification réussie.";
        }
        else
        {
            // Message d'erreur et lien pour retourner au formulaire d'authentification
            echo "Erreur : Email ou mot de passe incorrect.";
            echo '<br><a href="authentification.html">Retourner au formulaire d\'authentification</a>';
        }
    }
    catch (Exception $e)
    {
        // Gestion de l'exception "compte_desactive"
        if ($e->getMessage() === "compte_desactive")
        {
            $tempsRestant = $utilisateur->tempsRestantAvantReactivationCompte();
            $minutes = floor($tempsRestant / 60);
            $secondes = $tempsRestant % 60;

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
