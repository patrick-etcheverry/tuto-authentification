<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Barre de navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="fas fa-graduation-cap"></i>
            Tutoriel authentification</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link active"><i class="fas fa-user"></i> Connecté en tant que : <strong>XXXXX</strong></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-sign-out-alt"></i> Se déconnecter</a>
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
                            <a class="nav-link active" href="#">
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
                            <a class="nav-link" href="#">
                                <i class="fas fa-book"></i> Journal des connexions
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Contenu principal -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h3"><i class="fas fa-tachometer-alt"></i> Tableau de bord</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary"><i class="fas fa-file-export"></i> Exporter</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary"><i class="fas fa-share"></i> Partager</button>
                        </div>
                    </div>
                </div>

                <!-- Widgets -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="card text-bg-primary mb-3">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-user-check"></i> Utilisateurs actifs</h5>
                                <p class="card-text">45 utilisateurs actifs.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-bg-success mb-3">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-sign-in-alt"></i> Connexions aujourd'hui</h5>
                                <p class="card-text">120 connexions réussies.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-bg-danger mb-3">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-ban"></i> Tentatives échouées</h5>
                                <p class="card-text">12 tentatives bloquées.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="mt-4"><i class="fas fa-list-alt"></i> Dernières actions</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Action</th>
                                <th>Utilisateur</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><i class="fas fa-user-plus"></i> Création d'un compte</td>
                                <td>admin@exemple.com</td>
                                <td><?= date('Y'); ?>-11-25</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td><i class="fas fa-user-times"></i> Tentative échouée</td>
                                <td>user@exemple.com</td>
                                <td><?= date('Y'); ?>-11-24</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>
