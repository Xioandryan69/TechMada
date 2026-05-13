<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Option GOLD - My Régime</title>
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/fontawesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <style>
        .gold-card {
            background: linear-gradient(135deg, #f5c518 0%, #e0a800 100%);
            border: none;
        }
        .gold-badge {
            background: linear-gradient(135deg, #f5c518, #e0a800);
            color: #000;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-success" href="<?= base_url('users/homepage') ?>">My Régime</a>
            <div class="ms-auto d-flex gap-2">
                <a class="btn btn-outline-light btn-sm" href="<?= base_url('users/wallet') ?>">
                    <i class="fas fa-wallet me-1"></i> Mon Wallet
                </a>
                <a class="btn btn-outline-light btn-sm" href="<?= base_url('users/homepage') ?>">
                    <i class="fas fa-arrow-left me-1"></i> Retour
                </a>
            </div>
        </div>
    </nav>

    <section class="py-5">
        <div class="container" style="max-width: 600px;">

            <!-- Carte GOLD principale -->
            <div class="card gold-card shadow mb-4 text-dark">
                <div class="card-body text-center py-5">
                    <i class="fas fa-star fa-3x mb-3"></i>
                    <h1 class="fw-bold">ABONNEMENT GOLD</h1>
                    <p class="fs-5 mb-0">Profitez de <strong>-<?= $goldDiscount ?>%</strong> sur tous les régimes</p>
                    <p class="text-dark opacity-75">Valable <strong><?= $goldDuration ?> jours</strong></p>
                </div>
            </div>

            <?php if ($isGold): ?>

                <!-- Déjà GOLD -->
                <div class="card shadow-sm border-0 border-start border-warning border-4 mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fas fa-check-circle fa-2x text-warning"></i>
                            <div>
                                <h5 class="fw-bold mb-1">Vous êtes déjà GOLD ✨</h5>
                                <p class="mb-0 text-muted">
                                    Votre abonnement est actif jusqu'au
                                    <strong><?= date('d/m/Y', strtotime($goldSubscription['date_fin'])) ?></strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <a href="<?= base_url('users/homepage') ?>" class="btn btn-success btn-lg">
                        <i class="fas fa-home me-2"></i> Retour à l'accueil
                    </a>
                </div>

            <?php else: ?>

                <!-- Pas encore GOLD -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted"><i class="fas fa-tag me-2"></i>Prix de l'abonnement</span>
                            <strong class="fs-5"><?= number_format($goldPrice, 2) ?> Ar</strong>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted"><i class="fas fa-wallet me-2"></i>Votre solde</span>
                            <strong class="fs-5 <?= ($wallet['solde'] >= $goldPrice) ? 'text-success' : 'text-danger' ?>">
                                <?= number_format($wallet['solde'], 2) ?> Ar
                            </strong>
                        </div>

                        <hr>

                        <?php if ($wallet['solde'] < $goldPrice): ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Solde insuffisant. Il vous manque
                                <strong><?= number_format($goldPrice - $wallet['solde'], 2) ?> Ar</strong>.
                                <a href="<?= base_url('users/wallet') ?>" class="alert-link">Recharger mon wallet →</a>
                            </div>
                        <?php else: ?>
                            <button class="btn btn-warning btn-lg w-100 fw-bold" onclick="acheterGold()" id="btnBuy">
                                <i class="fas fa-star me-2"></i> Acheter GOLD — <?= number_format($goldPrice, 2) ?> Ar
                            </button>
                        <?php endif; ?>

                        <div id="goldMessage" class="mt-3"></div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Ce que vous obtenez :</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="fas fa-check text-warning me-2"></i> -<?= $goldDiscount ?>% sur tous les régimes</li>
                            <li class="mb-2"><i class="fas fa-check text-warning me-2"></i> Valable <?= $goldDuration ?> jours</li>
                            <li><i class="fas fa-check text-warning me-2"></i> Activé immédiatement</li>
                        </ul>
                    </div>
                </div>

            <?php endif; ?>

        </div>
    </section>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script>

    function acheterGold()
    {
        const btn    = document.getElementById('btnBuy');
        const msgDiv = document.getElementById('goldMessage');

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Traitement…';

        fetch("<?= base_url('users/wallet/gold/buy') ?>", {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                msgDiv.innerHTML =
                    "<div class='alert alert-success'>"
                    + "<i class='fas fa-star me-2'></i>"
                    + data.message
                    + "<br><small>Nouveau solde : " + data.new_balance + " Ar — Valide jusqu'au " + data.date_fin + "</small>"
                    + "</div>";
                setTimeout(() => location.reload(), 2500);
            } else {
                msgDiv.innerHTML =
                    "<div class='alert alert-danger'>"
                    + "<i class='fas fa-times-circle me-2'></i>"
                    + data.message
                    + "</div>";
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-star me-2"></i> Acheter GOLD';
            }
        })
        .catch(() => {
            msgDiv.innerHTML = "<div class='alert alert-danger'>Erreur réseau, réessayez.</div>";
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-star me-2"></i> Acheter GOLD';
        });
    }

    </script>
</body>
</html>