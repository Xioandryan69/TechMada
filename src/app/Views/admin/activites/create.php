<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une activité - Administration</title>
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
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-success text-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-plus-circle me-2"></i> Ajouter une nouvelle activité</h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="<?= base_url('admin/activites/store') ?>" method="post">
                                <?= csrf_field() ?>
                                
                                <div class="mb-3">
                                    <label for="nom" class="form-label fw-bold">Nom de l'activité</label>
                                    <input type="text" class="form-control" id="nom" name="nom" required placeholder="Ex: Jumping-Jack">
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label fw-bold">Description courte</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="calories_brulees" class="form-label fw-bold">Calories brulées</label>
                                        <input type="number" class="form-control" id="calories_brulees" name="calories_brulees" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="duree_minutes" class="form-label fw-bold">Durée (en minutes)</label>
                                        <input type="number" class="form-control" id="duree_minutes" name="duree_minutes" required>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="<?= base_url('admin/activites') ?>" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Annuler</a>
                                    <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Créer le activité</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>