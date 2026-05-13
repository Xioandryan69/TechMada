<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des activités - Administration</title>
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
                <h2 class="fw-bold">Liste des activités</h2>
                <a href="<?= base_url('admin/activites/create') ?>" class="btn btn-success"><i class="fas fa-plus me-1"></i> Nouvelle Activité</a>
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
                                    <th>Calories brulées</th>
                                    <th>Durée</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($activites as $activite): ?>
                                <tr>
                                    <td><?= esc($activite['id']) ?></td>
                                    <td class="fw-bold text-primary"><?= esc($activite['nom']) ?></td>
                                    <td><?= esc($activite['calories_brulees']) ?> kcal</td>
                                    <td><?= esc($activite['duree_minutes']) ?> minutes</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('admin/activites/edit/'.$activite['id']) ?>" class="btn btn-sm btn-primary" title="Modifier">
                                                <i class="fas fa-edit"></i> Modifier
                                            </a>
                                            <a href="<?= base_url('admin/activites/delete/'.$activite['id']) ?>" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Es-tu sûr de vouloir supprimer cette activitée ?');">
                                                <i class="fas fa-trash-alt"></i> Supprimer
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if(empty($activites)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Aucune activité trouvé.</td>
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