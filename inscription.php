<?php

// Importation de la classe Utilisateur pour gérer l'inscription
require_once 'utilisateur.class.php';

// Vérifie si le formulaire a été soumis via une requête POST
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    // Récupération des données envoyées par le formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Création d'une instance de la classe Utilisateur avec les données du formulaire
    $utilisateur = new Utilisateur($email, $password);

    try
    {
        // Appel à la méthode inscription() pour ajouter l'utilisateur à la base de données
        $utilisateur->inscription();
        echo "Inscription réussie.<br>";

        // Ajout d'un lien pour aller au formulaire d'authentification
        echo '<a href="authentification.html">Aller au formulaire d\'authentification</a>';
    }
    catch (Exception $e)
    {
        // Gestion des erreurs, par exemple si l'email est déjà utilisé
        echo "Erreur : " . $e->getMessage();
    }
}
