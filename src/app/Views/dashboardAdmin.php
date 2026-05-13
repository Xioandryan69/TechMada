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
    <section id="page-dashboard-admin" style="margin-top:3rem">
<?php
$conges = $conges ?? [];
$employees = $employees ?? [];
$departements = $departements ?? [];
$pendingConges = array_values(array_filter($conges, static fn ($conge) => ($conge['statut'] ?? '') === 'en_attente'));
$validatedConges = array_values(array_filter($conges, static fn ($conge) => ($conge['statut'] ?? '') === 'valide'));
$refusedConges = array_values(array_filter($conges, static fn ($conge) => ($conge['statut'] ?? '') === 'refuse'));
$recentConges = array_slice($conges, 0, 3);
?>
<div class="app-wrap">

  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon" style="background:var(--ink);border:1px solid rgba(255,255,255,.15)"><i class="bi bi-shield-check" style="color:var(--leaf)"></i></div>
      <div class="sidebar-brand-name">TechMada RH
        <span>Administration</span>
      </div>
    </div>
    <div class="sidebar-section">Gestion</div>
    <ul class="sidebar-nav">
      <li><a href="<?= site_url('admin/dashboard') ?>" class="active"><i class="bi bi-speedometer2"></i> Vue d'ensemble</a></li>
      <li>
        <a href="<?= site_url('rh/demandes') ?>">
          <i class="bi bi-inbox"></i> Toutes les demandes
          <span class="nav-badge alert"><?= count($pendingConges) ?></span>
        </a>
      </li>
      <li><a href="<?= site_url('admin/employes') ?>"><i class="bi bi-people"></i> Employés</a></li>
      <li><a href="<?= site_url('admin/departements') ?>"><i class="bi bi-building"></i> Départements</a></li>
      <li><a href="<?= site_url('admin/types-conges') ?>"><i class="bi bi-tags"></i> Types de congé</a></li>
      <li><a href="<?= site_url('admin/soldes-annuels') ?>"><i class="bi bi-sliders"></i> Soldes annuels</a></li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar" style="background:#5a2d82;width:32px;height:32px;font-size:.7rem">AD</div>
        <div><div class="user-name">Administrateur</div><div class="user-role">Admin système</div></div>
        <a href="<?= site_url('logout') ?>" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem"><i class="bi bi-box-arrow-right"></i></a>
      </div>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Vue d'ensemble</div>
        <div class="topbar-breadcrumb">Administration</div>
      </div>
      <div class="topbar-actions">
        <a href="<?= site_url('admin/employes') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-person-plus"></i> Ajouter un employé</a>
      </div>
    </div>

    <div class="content">

      <!-- Métriques admin -->
      <div class="metrics">
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-people"></i></div></div>
          <div class="metric-val"><?= count($employees) ?></div>
          <div class="metric-label">Employés actifs</div>
          <div class="metric-sub up"><i class="bi bi-arrow-up-short"></i> depuis la base</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div>
          <div class="metric-val"><?= count($pendingConges) ?></div>
          <div class="metric-label">Demandes en attente</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-calendar-check"></i></div></div>
          <div class="metric-val"><?= count($validatedConges) ?></div>
          <div class="metric-label">Approuvées ce mois</div>
          <div class="metric-sub up"><i class="bi bi-arrow-up-short"></i> chargées depuis la base</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-blue"><i class="bi bi-building"></i></div></div>
          <div class="metric-val"><?= count($departements) ?></div>
          <div class="metric-label">Départements</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-person-slash"></i></div></div>
          <div class="metric-val"><?= count($refusedConges) ?></div>
          <div class="metric-label">Absents aujourd'hui</div>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start">

        <!-- Demandes récentes -->
        <div class="data-card" style="margin:0">
          <div class="data-card-head">
            <h3>Demandes récentes</h3>
            <a href="<?= site_url('rh/demandes') ?>" style="font-size:.8rem;color:var(--forest);text-decoration:none">Tout voir →</a>
          </div>
          <table class="tbl">
            <thead>
              <tr><th>Employé</th><th>Type</th><th>Durée</th><th>Statut</th></tr>
            </thead>
            <tbody>
              <?php foreach ($recentConges as $conge): ?>
                <?php
                  $statut = $conge['statut'] ?? '';
                  $statutLabel = $statut === 'valide' ? 'approuvée' : ($statut === 'refuse' ? 'refusée' : 'en attente');
                  $statusClass = $statut === 'valide' ? 's-approuvee' : ($statut === 'refuse' ? 's-refusee' : 's-attente');
                ?>
                <tr>
                  <td>
                    <div style="display:flex;align-items:center;gap:7px">
                      <div class="avatar av-green" style="width:28px;height:28px;font-size:.62rem"><?= esc(substr(($conge['employe_prenom'] ?? '') . ' ' . ($conge['employe_nom'] ?? ''), 0, 2)) ?></div>
                      <span class="td-name" style="font-size:.84rem"><?= esc(trim(($conge['employe_prenom'] ?? '') . ' ' . ($conge['employe_nom'] ?? ''))) ?></span>
                    </div>
                  </td>
                  <td><span class="type-badge t-annuel"><?= esc($conge['type_libelle'] ?? 'Congé') ?></span></td>
                  <td class="td-mono"><?= esc((string) ($conge['nb_jours'] ?? 0)) ?> j</td>
                  <td><span class="statut <?= $statusClass ?>"><?= esc($statutLabel) ?></span></td>
                </tr>
              <?php endforeach; ?>
              <?php if (empty($recentConges)): ?>
                <tr><td colspan="4"><div class="empty"><i class="bi bi-inbox"></i><p>Aucune demande enregistrée.</p></div></td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Absents du jour + soldes critiques -->
        <div style="display:flex;flex-direction:column;gap:1rem">
          <div class="data-card" style="margin:0">
            <div class="data-card-head"><h3><i class="bi bi-person-slash" style="color:var(--muted);margin-right:5px"></i>Absents aujourd'hui</h3></div>
            <div style="padding:.75rem 1.1rem;display:flex;flex-direction:column;gap:.6rem">
              <?php foreach (array_slice($validatedConges, 0, 3) as $conge): ?>
                <div style="display:flex;align-items:center;gap:8px">
                  <div class="avatar av-green" style="width:30px;height:30px;font-size:.65rem"><?= esc(substr(($conge['employe_prenom'] ?? '') . ' ' . ($conge['employe_nom'] ?? ''), 0, 2)) ?></div>
                  <div>
                    <div style="font-size:.83rem;font-weight:500;color:var(--ink)"><?= esc(trim(($conge['employe_prenom'] ?? '') . ' ' . ($conge['employe_nom'] ?? ''))) ?></div>
                    <div style="font-size:.72rem;color:var(--muted)"><?= esc($conge['type_libelle'] ?? 'Congé') ?> · retour <?= esc($conge['date_fin'] ?? '-') ?></div>
                  </div>
                </div>
              <?php endforeach; ?>
              <?php if (empty($validatedConges)): ?>
                <div class="empty"><i class="bi bi-inbox"></i><p>Aucun absent aujourd'hui.</p></div>
              <?php endif; ?>
            </div>
          </div>
          <div class="flash flash-warn" style="margin:0">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span style="font-size:.8rem"><?= count($employees) ?> employés chargés depuis la base. <a href="<?= site_url('admin/employes') ?>" style="color:var(--warn);font-weight:500">Voir les employés →</a></span>
          </div>
        </div>

      </div>

    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>

</div>
</section>
</body>
</html>