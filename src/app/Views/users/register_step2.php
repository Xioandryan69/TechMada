<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Étape 2 - My Régime</title>
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/fontawesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <style>
        .step-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            gap: 0;
        }
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.95rem;
            flex-shrink: 0;
        }
        .step-circle.done   { background-color: #198754; color: white; }
        .step-circle.active { background-color: #198754; color: white; }
        .step-circle.inactive { background-color: #dee2e6; color: #6c757d; }
        .step-label {
            font-size: 0.78rem;
            margin-top: 4px;
            text-align: center;
            font-weight: 500;
        }
        .step-label.done, .step-label.active { color: #198754; }
        .step-label.inactive { color: #6c757d; }
        .step-line {
            flex: 1;
            height: 3px;
            max-width: 80px;
        }
        .step-line.done     { background-color: #198754; }
        .step-line.inactive { background-color: #dee2e6; }
        .step-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .imc-badge {
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-success" href="<?= base_url('/') ?>">My Régime</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-success ms-2" href="<?= base_url('/users/register') ?>"><- Retour</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">

                    <!-- Indicateur d'étapes -->
                    <div class="step-indicator">
                        <div class="step-wrapper">
                            <div class="step-circle done"><i class="fas fa-check" style="font-size:0.8rem"></i></div>
                            <div class="step-label done">Infos personnelles</div>
                        </div>
                        <div class="step-line done mx-2 mb-3"></div>
                        <div class="step-wrapper">
                            <div class="step-circle active">2</div>
                            <div class="step-label active">Infos de santé</div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4 p-md-5">
                            <h2 class="text-center mb-1 fw-bold">Vos informations de santé</h2>
                            <p class="text-center text-muted mb-4">Étape 2 sur 2 — Ces données nous aident à personnaliser votre régime</p>

                            <!-- Bienvenue personnalisée -->
                            <?php if(session()->get('register_step1.prenom')): ?>
                                <div class="alert alert-success alert-sm py-2 text-center" role="alert">
                                    <i class="fas fa-user-circle me-1"></i>
                                    Bonjour <strong><?= esc(session()->get('register_step1.prenom')) ?></strong> ! Plus qu'une étape.
                                </div>
                            <?php endif; ?>

                            <!-- Erreurs de validation -->
                            <?php if(isset($validation)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= $validation->listErrors() ?>
                                </div>
                            <?php endif; ?>

                            <form action="<?= base_url('users/register/step2') ?>" method="post" id="healthForm">
                                <?= csrf_field() ?>

                                <!-- Taille -->
                                <div class="mb-3">
                                    <label for="taille" class="form-label">
                                        Taille <span class="text-danger">*</span>
                                        <small class="text-muted">(en cm)</small>
                                    </label>
                                    <div class="input-group">
                                        <input type="number"
                                               class="form-control <?= (isset($validation) && $validation->hasError('taille')) ? 'is-invalid' : '' ?>"
                                               name="taille" id="taille"
                                               placeholder="ex : 175"
                                               value="<?= old('taille') ?>"
                                               min="100" max="250" step="0.1"
                                               required>
                                        <span class="input-group-text">cm</span>
                                        <?php if(isset($validation) && $validation->hasError('taille')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('taille') ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Poids -->
                                <div class="mb-3">
                                    <label for="poids" class="form-label">
                                        Poids <span class="text-danger">*</span>
                                        <small class="text-muted">(en kg)</small>
                                    </label>
                                    <div class="input-group">
                                        <input type="number"
                                               class="form-control <?= (isset($validation) && $validation->hasError('poids')) ? 'is-invalid' : '' ?>"
                                               name="poids" id="poids"
                                               placeholder="ex : 70"
                                               value="<?= old('poids') ?>"
                                               min="20" max="300" step="0.1"
                                               required>
                                        <span class="input-group-text">kg</span>
                                        <?php if(isset($validation) && $validation->hasError('poids')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('poids') ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- IMC calculé en temps réel -->
                                <div class="mb-4" id="imcBlock" style="display:none;">
                                    <label class="form-label">Votre IMC estimé</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="fs-3 fw-bold text-success" id="imcValue">—</span>
                                        <span class="imc-badge" id="imcLabel">—</span>
                                    </div>
                                    <div class="progress mt-2" style="height: 8px;">
                                        <div class="progress-bar" id="imcBar" role="progressbar" style="width: 0%; background-color: #198754;"></div>
                                    </div>
                                    <small class="text-muted">L'IMC sera recalculé précisément lors de votre première connexion.</small>
                                </div>

                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-user-plus me-2"></i>Finaliser mon inscription
                                    </button>
                                </div>

                                <div class="text-center">
                                    <a href="<?= base_url('/users/register') ?>" class="text-muted small">
                                        <i class="fas fa-arrow-left me-1"></i>Retour à l'étape 1
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2026 My Régime. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
        // Calcul IMC en temps réel
        const tailleInput = document.getElementById('taille');
        const poidsInput  = document.getElementById('poids');
        const imcBlock    = document.getElementById('imcBlock');
        const imcValue    = document.getElementById('imcValue');
        const imcLabel    = document.getElementById('imcLabel');
        const imcBar      = document.getElementById('imcBar');

        function calculerIMC() {
            const taille = parseFloat(tailleInput.value);
            const poids  = parseFloat(poidsInput.value);

            if (!taille || !poids || taille < 100 || poids < 20) {
                imcBlock.style.display = 'none';
                return;
            }

            const tailleM = taille / 100;
            const imc = (poids / (tailleM * tailleM)).toFixed(1);

            imcBlock.style.display = 'block';
            imcValue.textContent = imc;

            // Catégorie et couleur
            let label, color, pct;
            if (imc < 18.5) {
                label = 'Insuffisance pondérale'; color = '#0dcaf0'; pct = 20;
            } else if (imc < 25) {
                label = 'Poids normal ✓';          color = '#198754'; pct = 45;
            } else if (imc < 30) {
                label = 'Surpoids';                color = '#ffc107'; pct = 65;
            } else if (imc < 35) {
                label = 'Obésité modérée';         color = '#fd7e14'; pct = 80;
            } else {
                label = 'Obésité sévère';           color = '#dc3545'; pct = 95;
            }

            imcLabel.textContent = label;
            imcLabel.style.backgroundColor = color + '22';
            imcLabel.style.color = color;
            imcLabel.style.border = '1px solid ' + color;
            imcBar.style.width = pct + '%';
            imcBar.style.backgroundColor = color;
        }

        tailleInput.addEventListener('input', calculerIMC);
        poidsInput.addEventListener('input', calculerIMC);

        // Calcul initial si valeurs pré-remplies
        calculerIMC();
    </script>
</body>
</html>