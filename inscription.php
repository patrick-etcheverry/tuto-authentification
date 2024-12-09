<?php

// Importation de la classe Utilisateur pour gérer l'inscription
require_once 'utilisateur.class.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    // Récupération des données envoyées par le formulaire
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Création d'une instance de la classe Utilisateur avec les données du formulaire
    $utilisateur = new Utilisateur($email, $password);

    try
    {
        // Tentative d'inscription
        $utilisateur->inscription();

        // Si l'utilisateur a pu être inscrit en BD, affichage du succès
        echo "<h1>Inscription réussie !</h1>";
        echo '<a href="authentification.html">Se connecter</a>';
    }
catch (Exception $e)
{
    switch ($e->getMessage())
    {
        case "compte_existant":
            echo '<h1>Erreur : Compte existant</h1>';
            echo '<p>Ce compte existe déjà.</p>';
            echo '<a href="#">Mot de passe oublié ?</a><br>';
            echo '<a href="inscription.html">Retour au formulaire d\'inscription</a>';
            break;

        case "mdp_faible":
            echo '<h1>Erreur : Mot de passe invalide</h1>';
            echo '<p>Le mot de passe doit contenir au moins 8 caractères, une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.</p>';
            echo '<a href="inscription.php">Retour au formulaire d\'inscription</a>';
            break;

        default:
            echo "<h1>Une erreur inattendue est survenue</h1>";
            echo "<p>{$e->getMessage()}</p>";
            echo '<a href="inscription.php">Retour au formulaire d\'inscription</a>';
            break;
    }
}

}
