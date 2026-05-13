<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes recommandations</title>
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/fontawesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-success" href="<?= base_url('users/homepage') ?>">My Régime</a>
            <div class="ms-auto d-flex gap-2">
                <a class="btn btn-outline-light" href="<?= base_url('users/homepage') ?>"><i
                        class="fas fa-arrow-left me-1"></i> Retour</a>
                <a class="btn btn-outline-warning" href="<?= base_url('users/imc') ?>"><i
                        class="fas fa-heartbeat me-1"></i> Mon IMC</a>
            </div>
        </div>
    </nav>

    <section class="py-5">
        <div class="container">
            <div class="row mb-4 align-items-end">
                <div class="col-lg-8">
                    <h1 class="fw-bold mb-2">Mes recommandations personnalisées</h1>
                    <p class="text-muted mb-0">Le moteur s’appuie sur votre objectif, votre IMC, votre tranche d’âge,
                        health_rules et recommendations_weight pour préparer les régimes adaptés.</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <a href="<?= base_url('users/activities/recommended') ?>"
                        class="btn btn-outline-primary btn-lg me-2">
                        <i class="fas fa-table me-2"></i> Voir tableau activites
                    </a>
                    <form action="<?= base_url('users/recommendations/generate') ?>" method="post"
                        class="d-inline-block">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-magic me-2"></i> Générer et enregistrer
                        </button>
                    </form>
                </div>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <?php if (!empty($context)): ?>
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="text-muted small">Âge</div>
                                <div class="h4 mb-0"><?= esc($context['age']) ?> ans</div>
                                <small class="text-muted">
                                    <?= !empty($context['ageRange']) ? esc($context['ageRange']['age_min'] . ' - ' . $context['ageRange']['age_max']) . ' ans' : 'Tranche non trouvée' ?>
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="text-muted small">IMC actuel</div>
                                <div class="h4 mb-0">
                                    <?= esc(number_format((float) ($context['currentImc'] ?? 0), 2, '.', '')) ?></div>
                                <small class="text-muted">
                                    <?= !empty($context['objectif']) ? esc($context['objectif']['nom']) : 'Objectif non défini' ?>
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="text-muted small">IMC cible</div>
                                <div class="h4 mb-0">
                                    <?= !empty($context['targetImcMin']) ? esc(number_format((float) $context['targetImcMin'], 2, '.', '')) : '—' ?>
                                    -
                                    <?= !empty($context['targetImcMax']) ? esc(number_format((float) $context['targetImcMax'], 2, '.', '')) : '—' ?>
                                </div>
                                <small class="text-muted">selon health_rules</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="text-muted small">Poids recommandé</div>
                                <div class="h4 mb-0">
                                    <?= !empty($context['targetWeightMin']) ? esc(number_format((float) $context['targetWeightMin'], 2, '.', '')) : '—' ?>
                                    -
                                    <?= !empty($context['targetWeightMax']) ? esc(number_format((float) $context['targetWeightMax'], 2, '.', '')) : '—' ?>
                                    kg
                                </div>
                                <small class="text-muted">selon recommendations_weight</small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-seedling me-2 text-success"></i> Régimes proposés
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($regimes)): ?>
                                <div class="row g-3">
                                    <?php foreach ($regimes as $regime): ?>
                                        <div class="col-12">
                                            <div class="border rounded-3 p-3 bg-white">
                                                <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                                                    <div>
                                                        <h6 class="fw-bold mb-1"><?= esc($regime['nom']) ?></h6>
                                                        <p class="text-muted mb-2"><?= esc($regime['description']) ?></p>
                                                    </div>
                                                    <span
                                                        class="badge bg-<?= ((float) $regime['variation_poids'] < 0) ? 'danger' : (((float) $regime['variation_poids'] > 0) ? 'success' : 'secondary') ?>">
                                                        Variation
                                                        <?= esc(number_format((float) $regime['variation_poids'], 2, '.', '')) ?>
                                                        kg
                                                    </span>
                                                </div>
                                                <div class="d-flex flex-wrap gap-3 text-muted small">
                                                    <span><i class="fas fa-fire me-1"></i> <?= esc($regime['calories']) ?>
                                                        kcal</span>
                                                    <span><i class="fas fa-clock me-1"></i> <?= esc($regime['duree_jours']) ?>
                                                        jours</span>
                                                    <span><i class="fas fa-tag me-1"></i> <?= esc($regime['prix']) ?> MGA</span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php
                                $goldCtrl = new \App\Controllers\GoldController();
                                $priceInfo = $goldCtrl->calculatePrice($regime['prix'], session()->get('user_id'));
                                ?>

                                <div class="card">
                                    <h5><?= $regime['nom'] ?></h5>

                                    <?php if ($priceInfo['discount'] > 0): ?>
                                        <p>
                                            <span style="text-decoration: line-through; color: #999;">
                                                <?= number_format($priceInfo['original_price'], 2) ?> Ar
                                            </span>
                                            <br>
                                            <strong class="text-success">
                                                <?= number_format($priceInfo['final_price'], 2) ?> Ar
                                                <small>(-15% GOLD)</small>
                                            </strong>
                                        </p>
                                    <?php else: ?>
                                        <p><strong><?= number_format($priceInfo['final_price'], 2) ?> Ar</strong></p>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning mb-0">Aucun régime compatible n’a été trouvé pour votre
                                    objectif et votre IMC.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card shadow-sm border-0 h-100 mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-dumbbell me-2 text-primary"></i> Activités
                                associées</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($activites)): ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($activites as $activite): ?>
                                        <div class="list-group-item px-0">
                                            <div class="d-flex justify-content-between gap-3">
                                                <div>
                                                    <div class="fw-semibold"><?= esc($activite['nom']) ?></div>
                                                    <small class="text-muted"><?= esc($activite['description']) ?></small>
                                                </div>
                                                <div class="text-end text-muted small">
                                                    <div><?= esc($activite['calories_brulees']) ?> kcal</div>
                                                    <div><?= esc($activite['duree_minutes']) ?> min</div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted mb-0">Aucune activité suggérée pour le moment.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-clipboard-list me-2 text-warning"></i> Recommandations enregistrées</h5>
                        <a href="<?= base_url('users/recommendations/exportPdf') ?>" class="btn btn-sm btn-danger"><i class="fas fa-file-pdf me-1"></i> Exporter PDF</a>
                    </div>
                        <div class="card-body">
                            <?php if (!empty($savedRecommendations)): ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($savedRecommendations as $recommendation): ?>
                                        <div class="list-group-item px-0">
                                            <div class="fw-semibold text-success">
                                                <?= esc($recommendation['regime_nom'] ?? 'Régime') ?></div>
                                            <div class="text-muted small">
                                                <?= esc($recommendation['activite_nom'] ?? 'Aucune activité') ?></div>
                                            <small class="text-muted">
                                                Du <?= esc(date('d/m/Y', strtotime($recommendation['date_debut']))) ?> au
                                                <?= esc(date('d/m/Y', strtotime($recommendation['date_fin']))) ?>
                                            </small>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted mb-0">Aucune recommandation sauvegardée. Lancez la génération pour les
                                    enregistrer dans la table recommendations.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>