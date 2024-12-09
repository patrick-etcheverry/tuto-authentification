<?php
// Démarrage de la session pour accéder aux variables de session
session_start();

// Vérification de l'état d'authentification
if (!isset($_SESSION['authentifie']) || $_SESSION['authentifie'] !== true) {
    // Redirection vers la page d'authentification si l'utilisateur n'est pas connecté
    header('Location: authentification.html');
    exit;
}

// Récupération des informations de l'utilisateur connecté
$utilisateurConnecte = $_SESSION['utilisateur'] ?? null;
$emailUtilisateur = $utilisateurConnecte['email'] ?? 'Inconnu';

// Simule un journal des connexions en dur (données fictives)
$journalConnexions = [
    ['email' => 'admin@example.com', 'date' => '2024-11-25 10:45:00', 'statut' => 'Succès'],
    ['email' => 'user@example.com', 'date' => '2024-11-25 09:15:00', 'statut' => 'Échec'],
    ['email' => 'guest@example.com', 'date' => '2024-11-24 22:30:00', 'statut' => 'Succès'],
    ['email' => 'admin@example.com', 'date' => '2024-11-24 18:20:00', 'statut' => 'Échec']
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal des connexions</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Barre de navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin.php"><i class="fas fa-graduation-cap"></i> Tutoriel authentification</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link active"><i class="fas fa-user"></i> Connecté en tant que : <strong><?= $emailUtilisateur; ?></strong></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="deconnexion.php"><i class="fas fa-sign-out-alt"></i> Se déconnecter</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteneur principal -->
    <div class="container-fluid">
        <div class="row">
            <!-- Barre latérale -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="admin.php">
                                <i class="fas fa-tachometer-alt"></i> Tableau de bord
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-users"></i> Gestion des utilisateurs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-chart-line"></i> Statistiques
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-cogs"></i> Configuration
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="journal.php">
                                <i class="fas fa-book"></i> Journal des connexions
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Contenu principal -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h3"><i class="fas fa-book"></i> Journal des connexions</h1>
                </div>

                <!-- Tableau du journal -->
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Email</th>
                                <th>Date</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($journalConnexions as $index => $connexion): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($connexion['email']); ?></td>
                                    <td><?= htmlspecialchars($connexion['date']); ?></td>
                                    <td><?= htmlspecialchars($connexion['statut']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
