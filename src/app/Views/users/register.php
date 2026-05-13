<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - My Régime</title>
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/fontawesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body class="bg-light">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-success" href="<?= base_url('/') ?>">My Régime</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-success ms-2" href="<?= base_url('/') ?>"><-Retour</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Register Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4 p-md-5">
                            <h2 class="text-center mb-4 fw-bold">Créer un compte</h2>

                            <!-- Messages d'erreur -->
                            <?php if(session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i> <?= session()->getFlashdata('error') ?>
                                </div>
                            <?php endif; ?>

                            <!-- Messages de succès -->
                            <?php if(session()->getFlashdata('success')): ?>
                                <div class="alert alert-success" role="alert">
                                    <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
                                </div>
                            <?php endif; ?>

                            <!-- Erreurs de validation -->
                            <?php if(isset($validation)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= $validation->listErrors() ?>
                                </div>
                            <?php endif; ?>

                            <form action="<?= base_url('users/register') ?>" method="post">
                                <?= csrf_field() ?>

                                <div class="row">
                                    <!-- Nom -->
                                    <div class="col-md-6 mb-3">
                                        <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control <?= (isset($validation) && $validation->hasError('nom')) ? 'is-invalid' : '' ?>"
                                               name="nom"
                                               id="nom"
                                               placeholder="Votre nom"
                                               value="<?= old('nom') ?>"
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
                                               name="prenom"
                                               id="prenom"
                                               placeholder="Votre prénom"
                                               value="<?= old('prenom') ?>"
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
                                           name="email"
                                           id="email"
                                           placeholder="exemple@email.com"
                                           value="<?= old('email') ?>"
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
                                                name="genre"
                                                id="genre"
                                                required>
                                            <option value="" disabled <?= old('genre') ? '' : 'selected' ?>>Sélectionner...</option>
                                            <option value="H" <?= old('genre') === 'H' ? 'selected' : '' ?>>Homme</option>
                                            <option value="F" <?= old('genre') === 'F' ? 'selected' : '' ?>>Femme</option>
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
                                               name="date_naissance"
                                               id="date_naissance"
                                               value="<?= old('date_naissance') ?>"
                                               max="<?= date('Y-m-d', strtotime('-10 years')) ?>"
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
                                               name="password"
                                               id="password"
                                               placeholder="Minimum 6 caractères"
                                               required>
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
                                               name="password_confirm"
                                               id="password_confirm"
                                               placeholder="Répétez votre mot de passe"
                                               required>
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
                                        <i class="fas fa-user-plus me-2"></i>Créer mon compte
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

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2026 My Régime. Tous droits réservés.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
        // Toggle visibilité mot de passe
        document.getElementById('togglePassword').addEventListener('click', function () {
            const input = document.getElementById('password');
            const icon  = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });

        document.getElementById('toggleConfirm').addEventListener('click', function () {
            const input = document.getElementById('password_confirm');
            const icon  = document.getElementById('eyeIconConfirm');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    </script>
</body>
</html>