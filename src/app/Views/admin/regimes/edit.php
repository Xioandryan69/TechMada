<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Régime - Administration</title>
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
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i> Modifier le régime n°<?= esc($regime['id']) ?></h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="<?= base_url('admin/regimes/update/'.$regime['id']) ?>" method="post">
                                <?= csrf_field() ?>
                                
                                <div class="mb-3">
                                    <label for="nom" class="form-label fw-bold">Nom du régime</label>
                                    <input type="text" class="form-control" id="nom" name="nom" value="<?= esc($regime['nom']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label fw-bold">Description courte</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" required><?= esc($regime['description']) ?></textarea>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="calories" class="form-label fw-bold">Calories (par jour)</label>
                                        <input type="number" class="form-control" id="calories" name="calories" value="<?= esc($regime['calories']) ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="duree_jours" class="form-label fw-bold">Durée (en jours)</label>
                                        <input type="number" class="form-control" id="duree_jours" name="duree_jours" value="<?= esc($regime['duree_jours']) ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="prix" class="form-label fw-bold">Prix (Ar)</label>
                                        <input type="number" step="0.01" class="form-control" id="prix" name="prix" value="<?= esc($regime['prix']) ?>" required>
                                    </div>
                                </div>
                                
                                <h6 class="fw-bold mt-4 mb-3 border-bottom pb-2">Composition du régime (%)</h6>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="pourcentage_viande" class="form-label text-danger fw-medium"><i class="fas fa-drumstick-bite me-1"></i> Viande (%)</label>
                                        <input type="number" min="0" max="100" class="form-control" id="pourcentage_viande" name="pourcentage_viande" value="<?= esc($regime['pourcentage_viande']) ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="pourcentage_poisson" class="form-label text-info fw-medium"><i class="fas fa-fish me-1"></i> Poisson (%)</label>
                                        <input type="number" min="0" max="100" class="form-control" id="pourcentage_poisson" name="pourcentage_poisson" value="<?= esc($regime['pourcentage_poisson']) ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="pourcentage_volaille" class="form-label text-warning fw-medium"><i class="fas fa-egg me-1"></i> Volaille (%)</label>
                                        <input type="number" min="0" max="100" class="form-control" id="pourcentage_volaille" name="pourcentage_volaille" value="<?= esc($regime['pourcentage_volaille']) ?>" required>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4 border-top pt-3">
                                    <a href="<?= base_url('admin/regimes') ?>" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Annuler</a>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Mettre à jour</button>
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