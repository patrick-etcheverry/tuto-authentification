<?php
// Importation de la classe Utilisateur et des fichiers nécessaires
require_once 'utilisateur.class.php';
require_once 'bd.class.php';

// Vérification que le formulaire a été soumis via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    // Récupération de l'email soumis via le formulaire
    $email = $_POST['email'] ?? '';

    // Nettoyage de l'email pour supprimer tout caractère invalide ou inattendu.
    // Cela évite les erreurs (par exemple, un espace en trop) et réduit les risques
    // de tentatives de manipulation malveillante des données (failles xss par exemple).
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Vérifier que la chaîne récupérée a bien la forme d'un email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        echo "<h1>Erreur</h1>";
        echo "<p>Adresse email invalide.</p>";
        echo '<a href="mot_de_passe_oublie.html">Retourner au formulaire</a>';
        exit;
    }

    try
    {
        // Création d'une instance de la classe Utilisateur
        $utilisateur = new Utilisateur($email, '');

        // Génération du token de réinitialisation
        $token = $utilisateur->genererTokenReinitialisation();

        // Simuler l'envoi d'un email en affichant un lien fictif
        $lienReinitialisation = "reinitialisation_mot_de_passe.php?token=$token";

        echo "<h1>Lien de réinitialisation généré</h1>";
        echo "<p>Voici le lien de réinitialisation qui aurait été envoyé par email :</p>";
        echo "<a href='$lienReinitialisation'>$lienReinitialisation</a>";
        echo '<p><a href="authentification.html">Retour à la connexion</a></p>';
    }
    catch (Exception $e)
    {
        // Gestion des erreurs (par exemple, utilisateur introuvable)
        echo "<h1>Erreur</h1>";
        echo "<p>{$e->getMessage()}</p>";
        echo '<a href="mot_de_passe_oublie.html">Retourner au formulaire</a>';
    }
}
else
{
    // Si le fichier est accédé directement sans soumission du formulaire
    header('Location: mot_de_passe_oublie.html');
    exit;
}
