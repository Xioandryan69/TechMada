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
    <section id="page-mes-conges" style="margin-top:3rem">
<?php
$conges = $conges ?? [];
?>
<div class="app-wrap">

  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon"><i class="bi bi-briefcase"></i></div>
      <div class="sidebar-brand-name">TechMada RH<span>Espace employé</span></div>
    </div>
    <ul class="sidebar-nav" style="margin-top:1rem">
      <li><a href="<?= site_url('employee/dashboard') ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
      <li><a href="<?= site_url('employee/conges/create') ?>"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
      <li><a href="<?= site_url('employee/conges') ?>" class="active"><i class="bi bi-calendar3"></i> Mes demandes</a></li>
      <li><a href="<?= site_url('employee/profil') ?>"><i class="bi bi-person"></i> Mon profil</a></li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar av-green">SR</div>
        <div><div class="user-name">Soa Rakoto</div><div class="user-role">Employé · IT</div></div>
      </div>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Mes demandes de congé</div>
        <div class="topbar-breadcrumb"><a href="<?= site_url('employee/dashboard') ?>">Accueil</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Mes demandes</div>
      </div>
      <div class="topbar-actions">
        <a href="<?= site_url('employee/conges/create') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-plus-lg"></i> Nouvelle demande</a>
      </div>
    </div>

    <div class="content">
      <div class="data-card">
        <div class="data-card-head">
          <h3>Toutes mes demandes</h3>
          <div style="display:flex;gap:6px">
            <select class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto">
              <option value="">Tous les statuts</option>
              <option value="en_attente">En attente</option>
              <option value="valide">Approuvée</option>
              <option value="refuse">Refusée</option>
            </select>
          </div>
        </div>
        <table class="tbl">
          <thead>
            <tr><th>Type</th><th>Début</th><th>Fin</th><th>Durée</th><th>Statut</th><th>Commentaire RH</th><th>Action</th></tr>
          </thead>
          <tbody>
            <?php foreach ($conges as $conge): ?>
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
                <td style="font-size:.78rem;color:<?= $statut === 'refuse' ? 'var(--danger)' : 'var(--muted)' ?>"><?= esc($conge['commentaire_rh'] ?? '—') ?></td>
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
            <?php if (empty($conges)): ?>
              <tr><td colspan="7"><div class="empty"><i class="bi bi-inbox"></i><p>Aucune demande trouvée.</p></div></td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>

</div>
</section>

</body>
</html>