<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>
</head>
<body>
    <section id="page-dashboard-employe" style="margin-top:3rem">
<?php
$conges = $conges ?? [];
$soldes = $soldes ?? [];
$pendingConges = array_values(array_filter($conges, static fn ($conge) => ($conge['statut'] ?? '') === 'en_attente'));
$approvedConges = array_values(array_filter($conges, static fn ($conge) => ($conge['statut'] ?? '') === 'valide'));
$refusedConges = array_values(array_filter($conges, static fn ($conge) => ($conge['statut'] ?? '') === 'refuse'));
$latestConges = array_slice($conges, 0, 3);
$totalAttribue = array_sum(array_map(static fn ($solde) => (int) ($solde['jours_attribues'] ?? 0), $soldes));
$totalPris = array_sum(array_map(static fn ($solde) => (int) ($solde['jours_pris'] ?? 0), $soldes));
$totalRestant = max(0, $totalAttribue - $totalPris);
?>
<div class="app-wrap">

  <!-- SIDEBAR EMPLOYÉ -->
  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon"><i class="bi bi-briefcase"></i></div>
      <div class="sidebar-brand-name">TechMada RH<span>Espace employé</span></div>
    </div>
    <div class="sidebar-section">Menu</div>
    <ul class="sidebar-nav">
      <li><a href="<?= site_url('employee/dashboard') ?>" class="active"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
      <li><a href="<?= site_url('employee/conges/create') ?>"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
      <li>
        <a href="<?= site_url('employee/conges') ?>">
          <i class="bi bi-calendar3"></i> Mes demandes
          <span class="nav-badge alert"><?= count($pendingConges) ?></span>
        </a>
      </li>
      <li><a href="<?= site_url('employee/profil') ?>"><i class="bi bi-person"></i> Mon profil</a></li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar av-green">SR</div>
        <div>
          <div class="user-name">Soa Rakoto</div>
          <div class="user-role">Employé · IT</div>
        </div>
        <a href="<?= site_url('logout') ?>" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem" title="Déconnexion"><i class="bi bi-box-arrow-right"></i></a>
      </div>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Tableau de bord</div>
        <div class="topbar-breadcrumb">Accueil</div>
      </div>
      <div class="topbar-actions">
        <a href="<?= site_url('employee/conges/create') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem">
          <i class="bi bi-plus-lg"></i> Nouvelle demande
        </a>
      </div>
    </div>

    <div class="content">

      <?php if ($successMessage = session()->getFlashdata('success')): ?>
      <div class="flash flash-success">
        <i class="bi bi-check-circle-fill"></i>
        <?= esc($successMessage) ?>
      </div>
      <?php endif; ?>

      <!-- Métriques -->
      <div class="metrics">
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div>
          <div class="metric-val"><?= count($pendingConges) ?></div>
          <div class="metric-label">En attente</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-check-circle"></i></div></div>
          <div class="metric-val"><?= count($approvedConges) ?></div>
          <div class="metric-label">Approuvées</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-calendar-check"></i></div></div>
          <div class="metric-val"><?= $totalRestant ?></div>
          <div class="metric-label">Jours restants</div>
          <div class="metric-sub">sur <?= $totalAttribue ?> attribués</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-x-circle"></i></div></div>
          <div class="metric-val"><?= count($refusedConges) ?></div>
          <div class="metric-label">Refusée</div>
        </div>
      </div>

      <!-- Soldes de congés -->
      <div class="data-card">
        <div class="data-card-head"><h3>Mes soldes de congés — <?= date('Y') ?></h3></div>
        <div style="padding:1rem 1.25rem;display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem">
          <?php foreach ($soldes as $solde): ?>
            <?php
              $attribues = (int) ($solde['jours_attribues'] ?? 0);
              $pris = (int) ($solde['jours_pris'] ?? 0);
              $restants = max(0, $attribues - $pris);
              $taux = $attribues > 0 ? (int) round(($restants / $attribues) * 100) : 0;
              $barClass = $taux <= 20 ? 'warn' : '';
            ?>
            <div class="solde-card" style="margin:0">
              <div class="solde-header">
                <span class="solde-type"><?= esc($solde['type_nom'] ?? 'Congé') ?></span>
                <span class="solde-nums"><strong><?= $restants ?></strong> / <?= $attribues ?> j</span>
              </div>
              <div class="solde-bar"><div class="solde-fill <?= $barClass ?>" style="width:<?= $taux ?>%"></div></div>
              <div class="solde-label"><?= $restants ?> jours restants · <?= $pris ?> pris</div>
            </div>
          <?php endforeach; ?>
          <?php if (empty($soldes)): ?>
            <div class="empty" style="grid-column:1/-1">
              <i class="bi bi-inbox"></i>
              <p>Aucun solde trouvé pour cette année.</p>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Dernières demandes -->
      <div class="data-card">
        <div class="data-card-head">
          <h3>Mes dernières demandes</h3>
          <a href="<?= site_url('employee/conges') ?>" style="font-size:.8rem;color:var(--forest);text-decoration:none">Voir tout →</a>
        </div>
        <table class="tbl">
          <thead>
            <tr><th>Type</th><th>Du</th><th>Au</th><th>Durée</th><th>Statut</th><th>Action</th></tr>
          </thead>
          <tbody>
            <?php foreach ($latestConges as $conge): ?>
              <?php
                $statut = $conge['statut'] ?? '';
                $statutLabel = $statut === 'valide' ? 'approuvée' : ($statut === 'refuse' ? 'refusée' : 'en attente');
                $statusClass = $statut === 'valide' ? 's-approuvee' : ($statut === 'refuse' ? 's-refusee' : 's-attente');
              ?>
              <tr>
                <td><span class="type-badge t-annuel"><?= esc($conge['type_libelle'] ?? 'Congé') ?></span></td>
                <td class="td-muted"><?= esc($conge['date_debut'] ?? '-') ?></td>
                <td class="td-muted"><?= esc($conge['date_fin'] ?? '-') ?></td>
                <td class="td-mono"><?= esc((string) ($conge['nb_jours'] ?? 0)) ?> j</td>
                <td><span class="statut <?= $statusClass ?>"><?= esc($statutLabel) ?></span></td>
                <td>
                  <?php if ($statut === 'en_attente'): ?>
                    <form action="<?= site_url('employee/conges/' . ($conge['id'] ?? 0) . '/annuler') ?>" method="post" style="display:inline">
                      <?= csrf_field() ?>
                      <button class="btn-sm btn-cancel" type="submit"><i class="bi bi-x"></i> Annuler</button>
                    </form>
                  <?php else: ?>
                    <span class="td-muted" style="font-size:.75rem">—</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($latestConges)): ?>
              <tr><td colspan="6"><div class="empty"><i class="bi bi-inbox"></i><p>Aucune demande enregistrée.</p></div></td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span> — Projet CodeIgniter 4</div>
  </div>

</div>
</section>
</body>
</html>

