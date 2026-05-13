<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activites sportives recommandees</title>
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-success" href="<?= base_url('users/homepage') ?>">My Regime</a>
        <div class="ms-auto d-flex gap-2">
            <a class="btn btn-outline-light" href="<?= base_url('users/recommendations') ?>">Recommandations</a>
            <a class="btn btn-outline-warning" href="<?= base_url('users/imc') ?>">Mon IMC</a>
        </div>
    </div>
</nav>

<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
            <div>
                <h1 class="fw-bold mb-1">Activites sportives recommandees</h1>
                <p class="text-muted mb-0">Affichage des activites liees a vos suggestions, avec duree, calories et regles sante (health_rules, age_ranges, recommendations_weight).</p>
            </div>
            <a href="<?= base_url('users/recommendations') ?>" class="btn btn-success">Retour aux suggestions</a>
        </div>

        <?php if (!empty($context)): ?>
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">Objectif</div>
                            <div class="fw-semibold"><?= esc($context['objectif']['nom'] ?? 'Non defini') ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">Tranche d'age</div>
                            <div class="fw-semibold">
                                <?= !empty($context['ageRange']) ? esc($context['ageRange']['age_min'] . ' - ' . $context['ageRange']['age_max']) . ' ans' : 'Non definie' ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">IMC cible</div>
                            <div class="fw-semibold">
                                <?= esc(number_format((float) ($context['targetImcMin'] ?? 0), 2, '.', '')) ?> -
                                <?= esc(number_format((float) ($context['targetImcMax'] ?? 0), 2, '.', '')) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">Poids recommande</div>
                            <div class="fw-semibold">
                                <?= esc(number_format((float) ($context['targetWeightMin'] ?? 0), 2, '.', '')) ?> -
                                <?= esc(number_format((float) ($context['targetWeightMax'] ?? 0), 2, '.', '')) ?> kg
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Table des activites recommandees</h5>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($activitiesTable)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Activite</th>
                                    <th>Duree (min)</th>
                                    <th>Calories</th>
                                    <th>Regime associe</th>
                                    <th>Objectif</th>
                                    <th>Tranche d'age</th>
                                    <th>IMC cible</th>
                                    <th>Poids recommande</th>
                                    <th>Periode</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($activitiesTable as $row): ?>
                                    <tr>
                                        <td><?= esc($row['activite_nom'] ?? '-') ?></td>
                                        <td><?= esc($row['duree_minutes'] ?? '-') ?></td>
                                        <td><?= esc($row['calories_brulees'] ?? '-') ?> kcal</td>
                                        <td><?= esc($row['regime_nom'] ?? '-') ?></td>
                                        <td><?= esc($row['objectif_nom'] ?? '-') ?></td>
                                        <td>
                                            <?= isset($row['age_min'], $row['age_max']) ? esc($row['age_min'] . ' - ' . $row['age_max']) . ' ans' : '-' ?>
                                        </td>
                                        <td>
                                            <?= isset($row['imc_cible_min'], $row['imc_cible_max']) ? esc(number_format((float) $row['imc_cible_min'], 2, '.', '') . ' - ' . number_format((float) $row['imc_cible_max'], 2, '.', '')) : '-' ?>
                                        </td>
                                        <td>
                                            <?= isset($row['poids_recommande_min'], $row['poids_recommande_max']) ? esc(number_format((float) $row['poids_recommande_min'], 2, '.', '') . ' - ' . number_format((float) $row['poids_recommande_max'], 2, '.', '')) . ' kg' : '-' ?>
                                        </td>
                                        <td>
                                            <?= !empty($row['date_debut']) ? esc(date('d/m/Y', strtotime($row['date_debut']))) : '-' ?>
                                            -
                                            <?= !empty($row['date_fin']) ? esc(date('d/m/Y', strtotime($row['date_fin']))) : '-' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="p-4 text-muted">Aucune activite recommandee enregistree pour le moment.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
