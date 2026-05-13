<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon IMC</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Mon IMC</h1>
        <a href="<?= site_url('users/homepage') ?>" class="btn btn-outline-secondary">Retour</a>
    </div>

    <?php if (!empty($sante)): ?>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="p-3 bg-white border rounded">
                            <div class="text-muted small">Taille</div>
                            <div class="fs-4 fw-semibold"><?= esc($sante['taille']) ?> m</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-white border rounded">
                            <div class="text-muted small">Poids</div>
                            <div class="fs-4 fw-semibold"><?= esc($sante['poids']) ?> kg</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-white border rounded">
                            <div class="text-muted small">IMC</div>
                            <div class="fs-4 fw-semibold"><?= esc(number_format((float) $imc, 2, '.', '')) ?></div>
                        </div>
                    </div>
                </div>

                <hr>

                <h2 class="h5 mb-2">Categorie</h2>
                <?php if (!empty($categorie)): ?>
                    <span class="badge bg-<?= esc($categorie['couleur']) ?> fs-6"><?= esc($categorie['label']) ?></span>
                    <p class="mt-3 mb-0 text-muted"><?= esc($categorie['conseil']) ?></p>
                <?php else: ?>
                    <p class="text-muted mb-0">Aucune categorie disponible.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning mb-0">Aucune information de sante trouvee.</div>
    <?php endif; ?>
</div>
</body>
</html>
