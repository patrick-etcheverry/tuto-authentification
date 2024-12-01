<?php

require_once 'utilisateur.class.php'; // Import de la classe Utilisateur

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    // Récupération des données du formulaire
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Création de l'utilisateur avec les données saisies
    $utilisateur = new Utilisateur($email, $password);

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
