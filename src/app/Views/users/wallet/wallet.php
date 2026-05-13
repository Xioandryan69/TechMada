<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Portefeuille - My Régime</title>
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/fontawesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-success" href="<?= base_url('users/homepage') ?>">My Régime</a>
            <div class="ms-auto d-flex gap-2">
                <a class="btn btn-outline-light btn-sm" href="<?= base_url('users/homepage') ?>">
                    <i class="fas fa-arrow-left me-1"></i> Retour
                </a>
                <a class="btn btn-warning btn-sm" href="<?= base_url('users/wallet/gold') ?>">
                    <i class="fas fa-star me-1"></i> GOLD
                </a>
            </div>
        </div>
    </nav>

    <section class="py-5">
        <div class="container" style="max-width: 600px;">

            <div class="mb-4">
                <h1 class="fw-bold"><i class="fas fa-wallet me-2 text-success"></i> Mon Portefeuille</h1>
                <p class="text-muted">Rechargez votre solde avec un code promo</p>
            </div>

            <!-- Carte solde -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center py-5">
                    <p class="text-muted mb-1">Solde disponible</p>
                    <h2 class="fw-bold text-success mb-0">
                        <span id="solde"><?= number_format($wallet['solde'], 2) ?></span> Ar
                    </h2>
                </div>
            </div>

            <!-- Formulaire code promo -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-ticket-alt me-2 text-primary"></i> Entrer un code promo</h5>

                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                        <input
                            type="text"
                            id="code"
                            class="form-control form-control-lg"
                            placeholder="Ex: CODE123"
                            autocomplete="off"
                        >
                        <button class="btn btn-primary px-4" onclick="validerCode()">
                            Valider
                        </button>
                    </div>

                    <div id="message"></div>
                </div>
            </div>

            <!-- Lien Gold -->
            <div class="text-center mt-4">
                <a href="<?= base_url('users/wallet/gold') ?>" class="btn btn-outline-warning">
                    <i class="fas fa-star me-1"></i> Passer GOLD et profiter de -15% sur les régimes
                </a>
            </div>

        </div>
    </section>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script>

    function validerCode()
    {
        const code    = document.getElementById('code').value.trim();
        const msgDiv  = document.getElementById('message');

        if (!code) {
            msgDiv.innerHTML = "<div class='alert alert-warning'>Veuillez saisir un code.</div>";
            return;
        }

        msgDiv.innerHTML = "<div class='alert alert-secondary'>Vérification en cours…</div>";

        fetch("<?= base_url('users/wallet/verify') ?>", {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'code=' + encodeURIComponent(code)
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                msgDiv.innerHTML =
                    "<div class='alert alert-success'>"
                    + "<i class='fas fa-check-circle me-2'></i>"
                    + data.message
                    + "</div>";
                document.getElementById('solde').textContent = data.new_balance;
                document.getElementById('code').value = '';
            } else {
                msgDiv.innerHTML =
                    "<div class='alert alert-danger'>"
                    + "<i class='fas fa-times-circle me-2'></i>"
                    + data.message
                    + "</div>";
            }
        })
        .catch(() => {
            msgDiv.innerHTML = "<div class='alert alert-danger'>Erreur réseau, réessayez.</div>";
        });
    }

    // Valider avec Entrée
    document.getElementById('code').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') validerCode();
    });

    </script>
</body>
</html>