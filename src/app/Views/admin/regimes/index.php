<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Régimes - Administration</title>
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/fontawesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body class="bg-light">

    <!-- Navigation (Composant séparé) -->
    <?= $this->include('admin/templates/navbar') ?>

    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Liste des Régimes</h2>
                <a href="<?= base_url('admin/regimes/create') ?>" class="btn btn-success"><i class="fas fa-plus me-1"></i> Nouveau Régime</a>
            </div>

            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Composition</th>
                                    <th>Calories (jour)</th>
                                    <th>Durée</th>
                                    <th>Prix</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($regimes as $regime): ?>
                                <tr>
                                    <td><?= esc($regime['id']) ?></td>
                                    <td class="fw-bold text-primary"><?= esc($regime['nom']) ?></td>
                                    <td>
                                        <div class="small">
                                            <span class="text-danger" title="Viande"><i class="fas fa-drumstick-bite"></i> <?= esc($regime['pourcentage_viande']) ?>%</span> |
                                            <span class="text-info" title="Poisson"><i class="fas fa-fish"></i> <?= esc($regime['pourcentage_poisson']) ?>%</span> |
                                            <span class="text-warning" title="Volaille"><i class="fas fa-egg"></i> <?= esc($regime['pourcentage_volaille']) ?>%</span>
                                        </div>
                                    </td>
                                    <td><?= esc($regime['calories']) ?> kcal</td>
                                    <td><?= esc($regime['duree_jours']) ?> jours</td>
                                    <td><span class="badge bg-success"><?= number_format($regime['prix'], 0, ',', ' ') ?> Ar</span></td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('admin/regimes/edit/'.$regime['id']) ?>" class="btn btn-sm btn-primary" title="Modifier">
                                                <i class="fas fa-edit"></i> Modifier
                                            </a>
                                            <a href="<?= base_url('admin/regimes/delete/'.$regime['id']) ?>" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Es-tu sûr de vouloir supprimer ce régime ?');">
                                                <i class="fas fa-trash-alt"></i> Supprimer
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if(empty($regimes)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Aucun régime trouvé.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>