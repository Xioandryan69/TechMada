<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Étape 1 - My Régime</title>
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
        .step-circle.active {
            background-color: #198754;
            color: white;
        }
        .step-circle.inactive {
            background-color: #dee2e6;
            color: #6c757d;
        }
        .step-label {
            font-size: 0.78rem;
            margin-top: 4px;
            text-align: center;
            font-weight: 500;
        }
        .step-label.active  { color: #198754; }
        .step-label.inactive { color: #6c757d; }
        .step-line {
            flex: 1;
            height: 3px;
            background-color: #dee2e6;
            max-width: 80px;
        }
        .step-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
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
                        <a class="nav-link btn btn-outline-success ms-2" href="<?= base_url('/') ?>"><- Retour</a>
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
                            <div class="step-circle active">1</div>
                            <div class="step-label active">Infos personnelles</div>
                        </div>
                        <div class="step-line mx-2 mb-3"></div>
                        <div class="step-wrapper">
                            <div class="step-circle inactive">2</div>
                            <div class="step-label inactive">Infos de santé</div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4 p-md-5">
                            <h2 class="text-center mb-1 fw-bold">Créer un compte</h2>
                            <p class="text-center text-muted mb-4">Étape 1 sur 2 — Vos informations personnelles</p>

                            <!-- Erreur flash -->
                            <?php if(session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
                                </div>
                            <?php endif; ?>

                            <!-- Erreurs de validation -->
                            <?php if(isset($validation)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= $validation->listErrors() ?>
                                </div>
                            <?php endif; ?>

                            <form action="<?= base_url('users/register/step1') ?>" method="post">
                                <?= csrf_field() ?>

                                <div class="row">
                                    <!-- Nom -->
                                    <div class="col-md-6 mb-3">
                                        <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control <?= (isset($validation) && $validation->hasError('nom')) ? 'is-invalid' : '' ?>"
                                               name="nom" id="nom"
                                               placeholder="Votre nom"
                                               value="<?= old('nom', session()->get('register_step1.nom') ?? '') ?>"
                                               required>
                                        <?php if(isset($validation) && $validation->hasError('nom')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('nom') ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Prénom -->
                                    <div class="col-md-6 mb-3">
                                        <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control <?= (isset($validation) && $validation->hasError('prenom')) ? 'is-invalid' : '' ?>"
                                               name="prenom" id="prenom"
                                               placeholder="Votre prénom"
                                               value="<?= old('prenom', session()->get('register_step1.prenom') ?? '') ?>"
                                               required>
                                        <?php if(isset($validation) && $validation->hasError('prenom')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('prenom') ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">E-mail <span class="text-danger">*</span></label>
                                    <input type="email"
                                           class="form-control <?= (isset($validation) && $validation->hasError('email')) ? 'is-invalid' : '' ?>"
                                           name="email" id="email"
                                           placeholder="exemple@email.com"
                                           value="<?= old('email', session()->get('register_step1.email') ?? '') ?>"
                                           required>
                                    <?php if(isset($validation) && $validation->hasError('email')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="row">
                                    <!-- Genre -->
                                    <div class="col-md-6 mb-3">
                                        <label for="genre" class="form-label">Genre <span class="text-danger">*</span></label>
                                        <select class="form-select <?= (isset($validation) && $validation->hasError('genre')) ? 'is-invalid' : '' ?>"
                                                name="genre" id="genre" required>
                                            <option value="" disabled <?= old('genre', session()->get('register_step1.genre') ?? '') ? '' : 'selected' ?>>Sélectionner...</option>
                                            <option value="H" <?= old('genre', session()->get('register_step1.genre') ?? '') === 'H' ? 'selected' : '' ?>>Homme</option>
                                            <option value="F" <?= old('genre', session()->get('register_step1.genre') ?? '') === 'F' ? 'selected' : '' ?>>Femme</option>
                                        </select>
                                        <?php if(isset($validation) && $validation->hasError('genre')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('genre') ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Date de naissance -->
                                    <div class="col-md-6 mb-3">
                                        <label for="date_naissance" class="form-label">Date de naissance <span class="text-danger">*</span></label>
                                        <input type="date"
                                               class="form-control <?= (isset($validation) && $validation->hasError('date_naissance')) ? 'is-invalid' : '' ?>"
                                               name="date_naissance" id="date_naissance"
                                               value="<?= old('date_naissance', session()->get('register_step1.date_naissance') ?? '') ?>"
                                               max="<?= date('Y-m-d', strtotime('-15 years')) ?>"
                                               required>
                                        <?php if(isset($validation) && $validation->hasError('date_naissance')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('date_naissance') ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Mot de passe -->
                                <div class="mb-3">
                                    <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password"
                                               class="form-control <?= (isset($validation) && $validation->hasError('password')) ? 'is-invalid' : '' ?>"
                                               name="password" id="password"
                                               placeholder="Minimum 6 caractères" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye" id="eyeIcon"></i>
                                        </button>
                                        <?php if(isset($validation) && $validation->hasError('password')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('password') ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Confirmation mot de passe -->
                                <div class="mb-4">
                                    <label for="password_confirm" class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password"
                                               class="form-control <?= (isset($validation) && $validation->hasError('password_confirm')) ? 'is-invalid' : '' ?>"
                                               name="password_confirm" id="password_confirm"
                                               placeholder="Répétez votre mot de passe" required>
                                        <button class="btn btn-outline-secondary" type="button" id="toggleConfirm">
                                            <i class="fas fa-eye" id="eyeIconConfirm"></i>
                                        </button>
                                        <?php if(isset($validation) && $validation->hasError('password_confirm')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('password_confirm') ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        Suivant <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>

                                <p class="text-center mb-0">
                                    Déjà un compte ?
                                    <a href="<?= base_url('/users/login') ?>" class="text-success fw-bold">Se connecter</a>
                                </p>
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
        document.getElementById('togglePassword').addEventListener('click', function () {
            const input = document.getElementById('password');
            const icon  = document.getElementById('eyeIcon');
            input.type  = input.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
        document.getElementById('toggleConfirm').addEventListener('click', function () {
            const input = document.getElementById('password_confirm');
            const icon  = document.getElementById('eyeIconConfirm');
            input.type  = input.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>