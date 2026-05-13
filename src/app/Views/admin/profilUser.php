<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page user interfaces - My Régime</title>
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/fontawesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body class="bg-light">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-success" href="#">My Régime</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('admin/listUsers') ?>"><i class="fas fa-arrow-left me-1"></i> Retour à la liste </a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarUser" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> <?= esc(session()->get('username')) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item text-danger" href="<?= base_url('users/logout') ?>"><i class="fas fa-sign-out-alt me-1"></i> Se déconnecter</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <section class="py-5">
        <div class="container">
            <div class="row mb-4 align-items-center">
                <div class="col">
                    <h1 class="mb-1 fw-bold">Profil de <?= esc($userLastName) ?> <?= esc($userName) ?></h1>
                    <p class="text-muted">Gestion des informations de santé et objectifs du client</p>
                </div>
            </div>
            
            <div class="row g-4 mb-5">
                <!-- Complétion du profil -->
                <div class="col-lg-4 col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-user me-2 text-success"></i> Informations du client</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush mt-2">
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <span class="text-muted"><i class="fas fa-user me-2"></i> Nom complet</span>
                                    <span class="fw-medium"><?= esc($userName) ?> <?= esc($userLastName) ?></span>
                                </li>
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <span class="text-muted"><i class="fas fa-envelope me-2"></i> Email</span>
                                    <span class="fw-medium"><?= esc($userMail) ?></span>
                                </li>
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <span class="text-muted"><i class="fas fa-birthday-cake me-2"></i> Âge</span>
                                    <span class="fw-medium"><?= esc($userAge) ?> ans</span>
                                </li>
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <span class="text-muted"><i class="fas fa-arrows-alt-v me-2"></i> Taille</span>
                                    <span class="fw-medium"><?= esc($userTaille) ?> m</span>
                                </li>
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <span class="text-muted"><i class="fas fa-weight me-2"></i> Poids</span>
                                    <span class="fw-medium"><?= esc($userPoids) ?> kg</span>
                                </li>
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <span class="text-muted"><i class="fas fa-heartbeat me-2"></i> IMC</span>
                                    <span class="fw-medium"><?= esc($userImc) ?></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Choix des 3 objectifs -->
                <div class="col-lg-4 col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-bullseye me-2 text-warning"></i> Objectif du client</h5>
                        </div>
                        <div class="card-body">
                            <!-- Affiche l'objectif actuel s'il existe -->
                            <?php if(!empty($userObjectif)): ?>
                                <div class="alert alert-info mb-4">
                                    <i class="fas fa-info-circle me-2"></i> L'objectif actuel du client est : <strong><?= esc($userObjectif) ?></strong>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning mb-4">
                                    <i class="fas fa-exclamation-triangle me-2"></i> Ce client n'a pas encore défini d'objectif.
                                </div>
                            <?php endif; ?>
                            
                            <p class="text-muted mb-4">Ces objectifs déterminent les programmes qui lui seront proposés :</p>
                            
                            <div class="row g-3">
                                <!-- ID 1 = Perte de poids -->
                                <div class="col-12">
                                    <div class="border border-primary rounded-3 w-100 p-3 text-start d-flex align-items-center opacity-75">
                                        <i class="fas fa-arrow-down fa-2x me-3 text-primary" style="width: 40px;"></i>
                                        <div>
                                            <h6 class="fw-bold mb-0">Réduire son poids</h6>
                                            <small class="text-muted">Perdre des kilos de manière saine</small>
                                        </div>
                                    </div>
                                </div>
                                <!-- ID 2 = Prise de poids -->
                                <div class="col-12">
                                    <div class="border border-success rounded-3 w-100 p-3 text-start d-flex align-items-center opacity-75">
                                        <i class="fas fa-arrow-up fa-2x me-3 text-success" style="width: 40px;"></i>
                                        <div>
                                            <h6 class="fw-bold mb-0">Augmenter son poids</h6>
                                            <small class="text-muted">Gagner en masse musculaire et vitalité</small>
                                        </div>
                                    </div>
                                </div>
                                <!-- ID 3 = Maintien du poids -->
                                <div class="col-12">
                                    <div class="border border-info rounded-3 w-100 p-3 text-start d-flex align-items-center opacity-75">
                                        <i class="fas fa-balance-scale fa-2x me-3 text-info" style="width: 40px;"></i>
                                        <div>
                                            <h6 class="fw-bold mb-0">Atteindre / Maintenir son IMC idéal</h6>
                                            <small class="text-muted">Trouver le poids parfait selon votre taille</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Activités et Régimes -->
                <div class="col-lg-4 col-md-12">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-running me-2 text-primary"></i> Programmes suivis</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-4">Régimes et activités actuels du client :</p>
                            <?php if(!empty($userProgrammes)): ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach($userProgrammes as $prog): ?>
                                    <div class="list-group-item px-0">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1 fw-bold text-success"><i class="fas fa-utensils me-1"></i> <?= esc($prog['regime_nom'] ?? 'Aucun régime') ?></h6>
                                        </div>
                                        <p class="mb-1 fw-medium text-primary"><i class="fas fa-dumbbell me-1"></i> <?= esc($prog['activite_nom'] ?? 'Aucune activité') ?></p>
                                        <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> Du <?= date('d/m/Y', strtotime($prog['date_debut'])) ?> au <?= date('d/m/Y', strtotime($prog['date_fin'])) ?></small>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-secondary text-center">
                                    <i class="fas fa-info-circle mb-2 fa-2x text-muted"></i>
                                    <p class="mb-0">Ce client ne suit aucun programme actuellement.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2026 My Régime</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>    
</body>
</html>