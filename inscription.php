<?php

// Importation de la classe Utilisateur pour gérer l'inscription
require_once 'utilisateur.class.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    // Récupération des données envoyées par le formulaire
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    try
    {
        // Création d'une instance de la classe Utilisateur avec les données du formulaire
        // L'objet `Utilisateur` représente un utilisateur tenté de s'inscrire.
        $utilisateur = new Utilisateur($email, $password);

        // Tentative d'inscription
        $utilisateur->inscription();

        // Si l'utilisateur a pu être inscrit en base de données, affichage d'un message de succès
        echo "<h1>Inscription réussie !</h1>";
        echo '<a href="authentification.html">Se connecter</a>';
    }
    catch (Exception $e)
    {
        // Gestion des erreurs spécifiques levées par la méthode `inscription`
        if ($e->getMessage() === "compte_existant")
        {
            // L'email est déjà utilisé par un autre compte
            echo '<h1>Erreur : Compte existant</h1>';
            echo '<p>Ce compte existe déjà. Si vous avez oublié votre mot de passe, veuillez le réinitialiser.</p>';
            echo '<a href="#">Ré-initialiser le mot de passe</a><br>';
            echo '<a href="inscription.html">Retour au formulaire d\'inscription</a>';
        }
        else
        {
            // Gestion des autres erreurs
            echo "<h1>Erreur lors de l'inscription</h1>";
            echo "<p>Message technique : {$e->getMessage()}</p>";
        }
    }
}
