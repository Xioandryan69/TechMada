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
<?php
$employee = $employee ?? [];
$conges = $conges ?? [];
$soldes = $soldes ?? [];
$departements = $departements ?? [];
$departmentName = '-';
if (!empty($employee['departement_id']) && !empty($departements)) {
    foreach ($departements as $departement) {
        if ((int) ($departement['id'] ?? 0) === (int) $employee['departement_id']) {
            $departmentName = $departement['libelle'] ?? $departement['nom'] ?? '-';
            break;
        }
    }
}
?>
<section id="page-profil-employe" style="margin-top:3rem">
<div class="app-wrap">

  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon"><i class="bi bi-briefcase"></i></div>
      <div class="sidebar-brand-name">TechMada RH<span>Espace employé</span></div>
    </div>
    <ul class="sidebar-nav" style="margin-top:1rem">
      <li><a href="<?= site_url('employee/dashboard') ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
      <li><a href="<?= site_url('employee/conges/create') ?>"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
      <li><a href="<?= site_url('employee/conges') ?>"><i class="bi bi-calendar3"></i> Mes demandes</a></li>
      <li><a href="<?= site_url('employee/profil') ?>" class="active"><i class="bi bi-person"></i> Mon profil</a></li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar av-green"><?= esc(substr(trim(($employee['prenom'] ?? '') . ' ' . ($employee['nom'] ?? '')), 0, 2) ?: 'EP') ?></div>
        <div><div class="user-name"><?= esc(trim(($employee['prenom'] ?? '') . ' ' . ($employee['nom'] ?? '')) ?: 'Employé') ?></div><div class="user-role"><?= esc($departmentName) ?></div></div>
      </div>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Mon profil</div>
        <div class="topbar-breadcrumb"><a href="<?= site_url('employee/dashboard') ?>">Accueil</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Profil</div>
      </div>
      <div class="topbar-actions">
        <a href="<?= site_url('employee/conges/create') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-plus-lg"></i> Nouvelle demande</a>
      </div>
    </div>

    <div class="content">
      <div class="metrics">
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-person-badge"></i></div></div>
          <div class="metric-val"><?= esc((string) ($employee['id'] ?? '-')) ?></div>
          <div class="metric-label">Matricule</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-blue"><i class="bi bi-building"></i></div></div>
          <div class="metric-val"><?= esc($departmentName) ?></div>
          <div class="metric-label">Département</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-envelope"></i></div></div>
          <div class="metric-val" style="font-size:1rem"><?= esc($employee['email'] ?? '-') ?></div>
          <div class="metric-label">Adresse email</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-calendar-check"></i></div></div>
          <div class="metric-val"><?= count($conges) ?></div>
          <div class="metric-label">Demandes</div>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start">
        <div class="data-card" style="margin:0">
          <div class="data-card-head">
            <h3>Informations personnelles</h3>
          </div>
          <table class="tbl">
            <tbody>
              <tr><th style="width:220px">Nom</th><td><?= esc($employee['nom'] ?? '-') ?></td></tr>
              <tr><th>Prénom</th><td><?= esc($employee['prenom'] ?? '-') ?></td></tr>
              <tr><th>Email</th><td><?= esc($employee['email'] ?? '-') ?></td></tr>
              <tr><th>Département</th><td><?= esc($departmentName) ?></td></tr>
              <tr><th>Date d'embauche</th><td><?= esc($employee['date_embauche'] ?? '-') ?></td></tr>
              <tr><th>Statut</th><td><?= ((int) ($employee['actif'] ?? 0) === 1) ? 'Actif' : 'Inactif' ?></td></tr>
            </tbody>
          </table>
        </div>

        <div style="display:flex;flex-direction:column;gap:1rem">
          <div class="data-card" style="margin:0">
            <div class="data-card-head"><h3>Mes soldes</h3></div>
            <div style="padding:.75rem 1.1rem;display:flex;flex-direction:column;gap:.8rem">
              <?php foreach ($soldes as $solde): ?>
                <?php
                  $attribues = (int) ($solde['jours_attribues'] ?? 0);
                  $pris = (int) ($solde['jours_pris'] ?? 0);
                  $restants = max(0, $attribues - $pris);
                ?>
                <div>
                  <div style="display:flex;justify-content:space-between;gap:.5rem;font-size:.82rem;color:var(--ink)">
                    <span><?= esc($solde['type_nom'] ?? 'Congé') ?></span>
                    <span style="font-family:'DM Mono',monospace;color:var(--forest)"><?= $restants ?> / <?= $attribues ?> j</span>
                  </div>
                  <div class="solde-bar"><div class="solde-fill" style="width:<?= $attribues > 0 ? (int) round(($restants / $attribues) * 100) : 0 ?>%"></div></div>
                </div>
              <?php endforeach; ?>
              <?php if (empty($soldes)): ?>
                <div class="empty"><i class="bi bi-inbox"></i><p>Aucun solde disponible.</p></div>
              <?php endif; ?>
            </div>
          </div>

          <div class="data-card" style="margin:0">
            <div class="data-card-head"><h3>Dernières demandes</h3></div>
            <div style="padding:.75rem 1.1rem;display:flex;flex-direction:column;gap:.7rem">
              <?php foreach (array_slice($conges, 0, 3) as $conge): ?>
                <div style="display:flex;justify-content:space-between;gap:1rem;align-items:center;font-size:.82rem">
                  <div>
                    <div style="font-weight:500;color:var(--ink)"><?= esc($conge['type_libelle'] ?? 'Congé') ?></div>
                    <div style="color:var(--muted)"><?= esc(($conge['date_debut'] ?? '-') . ' → ' . ($conge['date_fin'] ?? '-')) ?></div>
                  </div>
                  <span class="statut <?= ($conge['statut'] ?? '') === 'valide' ? 's-approuvee' : (($conge['statut'] ?? '') === 'refuse' ? 's-refusee' : 's-attente') ?>"><?= esc($conge['statut'] ?? 'en_attente') ?></span>
                </div>
              <?php endforeach; ?>
              <?php if (empty($conges)): ?>
                <div class="empty"><i class="bi bi-inbox"></i><p>Aucune demande enregistrée.</p></div>
              <?php endif; ?>
            </div>
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
