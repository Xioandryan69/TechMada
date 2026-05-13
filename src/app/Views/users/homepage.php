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
                        <a class="nav-link active" href="<?= base_url('users/homepage') ?>"><i class="fas fa-home me-1"></i> Accueil </a>
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
                    <h1 class="mb-1 fw-bold">Bonjour, <?= esc($userLastName) ?> <?= esc($userName) ?> 👋</h1>
                    <p class="text-muted">Bienvenue sur votre espace personnel My Régime</p>
                </div>
            </div>
            
            <div class="row g-4 mb-5">
                <!-- Complétion du profil -->
                <div class="col-lg-4 col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-user-edit me-2 text-success"></i> Mon Profil</h5>
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
                            <div class="mt-4 text-center">
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editProfileModal"><i class="fas fa-edit me-1"></i>Modifier mes infos</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Choix des 3 objectifs -->
                <div class="col-lg-4 col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-bullseye me-2 text-warning"></i> Choisir mon objectif</h5>
                        </div>
                        <div class="card-body">
                            <!-- Affiche l'objectif actuel s'il existe -->
                            <?php if(!empty($userObjectif)): ?>
                                <div class="alert alert-info mb-4">
                                    <i class="fas fa-info-circle me-2"></i> Votre objectif actuel est : <strong><?= esc($userObjectif) ?></strong>
                                </div>
                            <?php endif; ?>
                            
                            <p class="text-muted mb-4">Sélectionnez l'un des 3 objectifs ci-dessous pour adapter vos futurs programmes :</p>

                            <?php
                                $objectifAugmenterId = 0;
                                $objectifReduireId = 0;
                                $objectifImcId = 0;

                                if (!empty($objectifs)) {
                                    foreach ($objectifs as $objectif) {
                                        $nom = strtolower(trim($objectif['nom']));

                                        if (str_contains($nom, 'prise') || str_contains($nom, 'augment')) {
                                            $objectifAugmenterId = (int) $objectif['id'];
                                        } elseif (str_contains($nom, 'perte') || str_contains($nom, 'redu')) {
                                            $objectifReduireId = (int) $objectif['id'];
                                        } elseif (str_contains($nom, 'maintien') || str_contains($nom, 'imc')) {
                                            $objectifImcId = (int) $objectif['id'];
                                        }
                                    }
                                }
                            ?>

                            <form action="<?= base_url('users/updateObjectif') ?>" method="post">
                                <?= csrf_field() ?>

                                <div class="list-group mb-3">
                                    <label class="list-group-item d-flex align-items-center gap-3 py-3">
                                        <input class="form-check-input m-0" type="radio" name="objectif_id" value="<?= esc($objectifAugmenterId) ?>" <?= ((int) $currentObjectifId === (int) $objectifAugmenterId) ? 'checked' : '' ?> required>
                                        <span>
                                            <span class="d-block fw-bold">Augmenter poids</span>
                                            <small class="text-muted">Prise de poids saine</small>
                                        </span>
                                    </label>

                                    <label class="list-group-item d-flex align-items-center gap-3 py-3">
                                        <input class="form-check-input m-0" type="radio" name="objectif_id" value="<?= esc($objectifReduireId) ?>" <?= ((int) $currentObjectifId === (int) $objectifReduireId) ? 'checked' : '' ?> required>
                                        <span>
                                            <span class="d-block fw-bold">Réduire poids</span>
                                            <small class="text-muted">Perte de poids progressive</small>
                                        </span>
                                    </label>

                                    <label class="list-group-item d-flex align-items-center gap-3 py-3">
                                        <input class="form-check-input m-0" type="radio" name="objectif_id" value="<?= esc($objectifImcId) ?>" <?= ((int) $currentObjectifId === (int) $objectifImcId) ? 'checked' : '' ?> required>
                                        <span>
                                            <span class="d-block fw-bold">Atteindre IMC idéal</span>
                                            <small class="text-muted">Atteindre ou maintenir un IMC normal</small>
                                        </span>
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-warning w-100 fw-semibold">
                                    <i class="fas fa-save me-2"></i> Enregistrer mon objectif
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Section Activités et Régimes -->
                <div class="col-lg-4 col-md-12">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-running me-2 text-primary"></i> Mes Programmes</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-4">Vos régimes et activités actuels :</p>
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
                                    <p class="mb-0">Aucun programme en cours. Choisissez un objectif pour recevoir des recommandations.</p>
                                </div>
                            <?php endif; ?>
                            
                            <div class="mt-4 text-center">
                                <a href="<?= base_url('users/recommendations') ?>" class="btn btn-outline-primary w-100"><i class="fas fa-search me-1"></i> Obtenir de nouveaux programmes</a>
                                <a href="<?= base_url('users/activities/recommended') ?>" class="btn btn-outline-success w-100 mt-2"><i class="fas fa-table me-1"></i> Tableau activites recommandees</a>
                                 <a href="<?= base_url('users/wallet/') ?>" class="btn btn-outline-success w-100 mt-2"><i class="fas fa-table me-1"></i> 💰 Mon Portefeuille</a>
                                  <a href="<?= base_url('users/wallet/gold') ?>" class="btn btn-outline-success w-100 mt-2"><i class="fas fa-table me-1"></i> ✨ Passer GOLD (-15%</a>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Modal pour éditer le profil -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="editProfileModalLabel"><i class="fas fa-edit me-2"></i> Modifier mon profil</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= base_url('users/updateProfile') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" value="<?= esc($userName) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" value="<?= esc($userLastName) ?>" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="taille" class="form-label">Taille (en mètres)</label>
                                <!-- step="0.01" permet de mettre des valeurs comme 1.75 -->
                                <input type="number" step="0.01" class="form-control" id="taille" name="taille" value="<?= esc($userTaille) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="poids" class="form-label">Poids (en kg)</label>
                                <input type="number" step="0.1" class="form-control" id="poids" name="poids" value="<?= esc($userPoids) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="date_naissance" class="form-label">Date de naissance</label>
                                <input type="date"
                                name="date_naissance" id="date_naissance"
                                value="<?= $dateNaissance ?>" 
                                max="<?= date('Y-m-d', strtotime('-15 years')) ?>"
                                required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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