<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Administration - My Régime</title>
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/fontawesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body class="bg-light">

    <!-- Navigation (Composant séparé) -->
    <?= $this->include('admin/templates/navbar') ?>

    <!-- Dashboard Content -->
    <section class="py-5">
        <div class="container">
            <h1 class="mb-4 fw-bold">Tableau de bord</h1>
            
            <div class="row g-4 mb-5">
                <!-- Chiffres clés -->
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 bg-success text-white h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title"><i class="fas fa-users me-2"></i> Utilisateurs Inscrits</h5>
                            <p class="card-text display-4 fw-bold"><?= $nbUsers ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 bg-primary text-white h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title"><i class="fas fa-chart-line me-2"></i> Revenus Totaux</h5>
                            <!-- Formatage du prix en Ariary (ou ta monnaie) -->
                            <p class="card-text display-4 fw-bold"><?= number_format($revenus, 0, ',', ' ') ?> Ar</p> 
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0 bg-info text-white h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title"><i class="fas fa-clipboard-list me-2"></i> Régimes Vendus</h5>
                            <p class="card-text display-4 fw-bold"><?= $nbRegimesVendus ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Statistiques Graphiques -->
            <div class="row g-4">
                <div class="col-md-8">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-money-bill-wave me-2"></i> Évolution des revenus</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="revenusChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-bullseye me-2"></i> Répartition des objectifs</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="objectifsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2026 My Régime. Espace Administration.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>    
    <!-- Chart.js pour les graphiques -->
    <script src="<?= base_url('assets/js/chart.min.js') ?>"></script>
    
    <script>
        // --- Graphique des Revenus (Ligne) ---
        const dataRevenus = <?= $revenusParMois ?>;
        const labelsRevenus = dataRevenus.map(item => item.mois);
        const valeursRevenus = dataRevenus.map(item => item.total);

        const ctxRevenus = document.getElementById('revenusChart').getContext('2d');
        new Chart(ctxRevenus, {
            type: 'line',
            data: {
                labels: labelsRevenus,
                datasets: [{
                    label: 'Revenus (Ar)',
                    data: valeursRevenus,
                    borderColor: '#198754', // Vert bootstrap
                    backgroundColor: 'rgba(25, 135, 84, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3 // Courbe un peu lissée
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // --- Graphique des Objectifs (Camembert) ---
        const dataObjectifs = <?= $objectifsCount ?>;
        const labelsObjectifs = dataObjectifs.map(item => item.nom);
        const valeursObjectifs = dataObjectifs.map(item => item.total);

        const ctxObjectifs = document.getElementById('objectifsChart').getContext('2d');
        new Chart(ctxObjectifs, {
            type: 'doughnut',
            data: {
                labels: labelsObjectifs,
                datasets: [{
                    data: valeursObjectifs,
                    backgroundColor: [
                        '#0d6efd', // Bleu
                        '#ffc107', // Jaune
                        '#dc3545', // Rouge
                        '#20c997'  // Teal
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>