<?php
// Démarrage de la session pour pouvoir la détruire
session_start();

/**
 * Destruction complète de la session.
 * 
 * Cette opération permet de :
 * - Supprimer toutes les variables de session.
 * - Détruire l'identifiant de session côté serveur.
 * - Supprimer le cookie de session côté client.
 */

// Suppression des variables de session
$_SESSION = [];

// Destruction de la session côté serveur
session_destroy();

// Suppression du cookie de session côté client
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirection vers la page d'accueil ou le formulaire d'authentification
header('Location: authentification.html');
exit;
