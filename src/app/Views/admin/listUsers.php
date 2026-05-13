<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des utilisateurs - Admin My Régime</title>
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/fontawesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body class="bg-light">

    <!-- Navigation (Composant séparé) -->
    <?= $this->include('admin/templates/navbar') ?>

    <!-- Main Content -->
    <section class="py-5">
        <div class="container">
            <div class="row mb-4 align-items-center">
                <div class="col">
                    <h1 class="mb-1 fw-bold"><i class="fas fa-users text-success me-2"></i> Liste des Clients</h1>
                    <p class="text-muted">Gérez les comptes et consultez les profils de vos utilisateurs</p>
                </div>
            </div>

            <div class="row g-4">
                <?php if (!empty($users) && is_array($users)): ?>
                    <?php foreach ($users as $user) : ?>
                        <div class="col-md-4 col-lg-3">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <h5 class="card-title fw-bold mb-1"><?= esc($user['nom']) ?> <?= esc($user['prenom']) ?></h5>
                                    <p class="text-muted small mb-3"><i class="fas fa-envelope me-1"></i> <?= esc($user['email']) ?></p>
                                </div>
                                <div class="card-footer bg-white border-0 text-center pb-3">
                                    <a href="<?= base_url('admin/profilUser/' . esc($user['id'])) ?>" class="btn btn-outline-success btn-sm w-100">
                                        <i class="fas fa-eye me-1"></i> Voir le profil
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info border-0 shadow-sm text-center py-4">
                            <i class="fas fa-info-circle fa-2x mb-3 text-info"></i>
                            <h5>Aucun utilisateur trouvé</h5>
                            <p class="mb-0 text-muted">Il n'y a pas encore de clients inscrits sur la plateforme.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2026 My Régime - Administration</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>    
</body>
</html>