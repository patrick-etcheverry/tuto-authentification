<?php
// Importation des dépendances nécessaires
require_once 'utilisateur.class.php';
require_once 'bd.class.php';

// Démarrage de la session (si nécessaire)
session_start();

// Récupération des données du formulaire
$password = $_POST['password'] ?? '';
$passwordConfirm = $_POST['password_confirm'] ?? '';
$token = $_POST['token'] ?? '';

// Validation des champs
if (empty($password) || empty($passwordConfirm) || empty($token))
{
    echo "<h1>Erreur</h1>";
    echo "<p>Tous les champs sont requis.</p>";
    echo '<a href="reinitialisation_mot_de_passe.php?token=' . htmlspecialchars($token) . '">Retour au formulaire</a>';
    exit;
}

// Vérification de la correspondance des mots de passe
if ($password !== $passwordConfirm)
{
    echo "<h1>Erreur</h1>";
    echo "<p>Les mots de passe ne correspondent pas.</p>";
    echo '<a href="reinitialisation_mot_de_passe.php?token=' . htmlspecialchars($token) . '">Retour au formulaire</a>';
    exit;
}

try
{
    // Connexion à la base de données
    $baseDeDonnees = BD::getInstancePdo();

    // Vérification du token et récupération de l'utilisateur
    $requete = $baseDeDonnees->prepare(
        'SELECT identifiant, expiration_token FROM utilisateurs WHERE token_reinitialisation = :token'
    );
    $requete->execute(['token' => htmlspecialchars($token)]);
    $utilisateur = $requete->fetch(PDO::FETCH_ASSOC);

    if (!$utilisateur)
    {
        echo "<h1>Erreur</h1>";
        echo "<p>Token invalide ou inexistant.</p>";
        exit;
    }

    // Vérification de la validité temporelle du token
    $expiration = strtotime($utilisateur['expiration_token']);
    if ($expiration < time())
    {
        echo "<h1>Erreur</h1>";
        echo "<p>Le token a expiré. Veuillez demander un nouveau lien de réinitialisation.</p>";
        exit;
    }

    // Création d'une instance utilisateur pour vérifier la robustesse du mot de passe
    $utilisateurInstance = new Utilisateur('', '');
    if (!$utilisateurInstance->estRobuste($password))
    {
        echo "<h1>Erreur</h1>";
        echo "<p>Le mot de passe ne respecte pas les critères de robustesse.</p>";
        echo '<a href="reinitialisation_mot_de_passe.php?token=' . htmlspecialchars($token) . '">Retour au formulaire</a>';
        exit;
    }

    // Hachage du nouveau mot de passe 
    $passwordHache = password_hash($password, PASSWORD_BCRYPT);

    //Mise à jour du mot de passe en BD
    $requete = $baseDeDonnees->prepare(
        'UPDATE utilisateurs 
         SET password = :password, token_reinitialisation = NULL, expiration_token = NULL 
         WHERE identifiant = :id'
    );
    $requete->execute([
        'password' => $passwordHache,
        'id' => $utilisateur['identifiant'],
    ]);

    echo "<h1>Succès</h1>";
    echo "<p>Votre mot de passe a été réinitialisé avec succès.</p>";
    echo '<a href="authentification.html">Retour à la connexion</a>';
}
catch (Exception $e)
{
    echo "<h1>Erreur</h1>";
    echo "<p>Une erreur inattendue s'est produite. Veuillez réessayer plus tard.</p>";
    exit;
}
