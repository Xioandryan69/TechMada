<!-- Navigation Admin -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-success" href="<?= base_url('admin/dashboard') ?>">My Régime - Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('admin/dashboard') ?>"><i class="fas fa-chart-pie me-1"></i> Tableau de bord </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('admin/listUsers') ?>"><i class="fas fa-users me-1"></i> Utilisateurs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('admin/regimes') ?>"><i class="fas fa-utensils me-1"></i> Régimes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('admin/activites') ?>"><i class="fas fa-utensils me-1"></i> Activités </a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i> <?= esc(session()->get('username') ?? 'Admin') ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item text-danger" href="<?= base_url('admin/logout') ?>"><i class="fas fa-sign-out-alt me-1"></i> Se déconnecter</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>