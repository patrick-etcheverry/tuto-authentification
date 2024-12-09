<?php
// Importation des dépendances nécessaires
require_once 'utilisateur.class.php';
require_once 'bd.class.php';

// Démarrage de la session (si nécessaire pour conserver des informations utilisateur)
session_start();

// Vérification de la présence du token dans l'URL
if (!isset($_GET['token']))
{
    echo "<h1>Erreur</h1>";
    echo "<p>Token de réinitialisation manquant.</p>";
    exit;
}

// Récupération et nettoyage du token
$token = htmlspecialchars($_GET['token']); // Nettoyage pour éviter des failles XSS

// Vérification de la validité du token
try
{
    $baseDeDonnees = BD::getInstancePdo();

    // Recherche du token en base de données
    $requete = $baseDeDonnees->prepare(
        'SELECT identifiant, expiration_token FROM utilisateurs WHERE token_reinitialisation = :token'
    );
    $requete->execute(['token' => $token]);
    $utilisateur = $requete->fetch(PDO::FETCH_ASSOC);

    if (!$utilisateur)
    {
        echo "<h1>Erreur</h1>";
        echo "<p>Token invalide ou inexistant.</p>";
        exit;
    }

    // Vérification de la date d'expiration du token
    $expiration = strtotime($utilisateur['expiration_token']);
    if ($expiration < time())
    {
        echo "<h1>Erreur</h1>";
        echo "<p>Le token a expiré. Veuillez demander un nouveau lien de réinitialisation.</p>";
        exit;
    }
}
catch (Exception $e)
{
    echo "<h1>Erreur</h1>";
    echo "<p>Une erreur inattendue s'est produite : " . $e->getMessage() . "</p>";
    exit;
}

// Si le token est valide, afficher le formulaire pour définir un nouveau mot de passe
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">Réinitialisation du mot de passe</h3>
                        <form action="traiter_reinitialisation.php" method="POST">
                            <div class="mb-3">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirm" class="form-label">Confirmez le mot de passe</label>
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                            </div>
                            <!-- Inclusion du token dans le formulaire -->
                            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Réinitialiser le mot de passe</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>