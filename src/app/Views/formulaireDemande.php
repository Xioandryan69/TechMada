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
    <section id="page-form-conge" style="margin-top:3rem">
<?php
$typesConge = $typesConge ?? [];
$soldes = $soldes ?? [];
?>
<div class="app-wrap">

  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon"><i class="bi bi-briefcase"></i></div>
      <div class="sidebar-brand-name">TechMada RH<span>Espace employé</span></div>
    </div>
    <ul class="sidebar-nav" style="margin-top:1rem">
      <li><a href="<?= site_url('employee/dashboard') ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
      <li><a href="<?= site_url('employee/conges/create') ?>" class="active"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
      <li><a href="<?= site_url('employee/conges') ?>"><i class="bi bi-calendar3"></i> Mes demandes</a></li>
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
        <div class="topbar-title">Nouvelle demande de congé</div>
        <div class="topbar-breadcrumb">
          <a href="<?= site_url('employee/dashboard') ?>">Accueil</a>
          <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Nouvelle demande
        </div>
      </div>
    </div>

    <div class="content">

      <div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start" class="form-layout">

        <!-- Formulaire principal -->
        <div>
          <form class="form-section" action="<?= site_url('employee/conges') ?>" method="post">
            <?= csrf_field() ?>
            <h3>Détails de la demande</h3>

            <div class="f-group" style="margin-bottom:1rem">
              <label class="f-label">Type de congé <span style="color:var(--danger)">*</span></label>
              <select class="f-select" name="type_conge_id" required>
                <option value="">-- Choisir un type --</option>
                <?php foreach ($typesConge as $typeConge): ?>
                  <option value="<?= esc($typeConge['id']) ?>">
                    <?= esc($typeConge['nom'] ?? 'Type de congé') ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-grid-2" style="margin-bottom:1rem">
              <div class="f-group">
                <label class="f-label">Date de début <span style="color:var(--danger)">*</span></label>
                <input type="date" class="f-input" name="date_debut" value="2025-06-23"/>
              </div>
              <div class="f-group">
                <label class="f-label">Date de fin <span style="color:var(--danger)">*</span></label>
                <input type="date" class="f-input" name="date_fin" value="2025-06-27"/>
              </div>
            </div>

            <div class="f-computed">
              <div class="f-computed-num"><i class="bi bi-calculator"></i></div>
              <div class="f-computed-label">Les jours seront calculés à l'enregistrement de la demande.</div>
            </div>

            <div class="f-group" style="margin-bottom:1rem">
              <label class="f-label">Motif (optionnel)</label>
              <textarea class="f-textarea" name="motif" required placeholder="Précisez le motif de votre demande si nécessaire..."></textarea>
              <div class="f-hint">Le motif est visible par le responsable RH.</div>
            </div>

            <div class="form-actions">
              <button class="btn-forest" type="submit"><i class="bi bi-send"></i> Soumettre la demande</button>
              <a href="<?= site_url('employee/dashboard') ?>" class="btn-secondary"><i class="bi bi-x"></i> Annuler</a>
            </div>
          </form>
        </div>

        <!-- Panneau latéral : solde & règles -->
        <div style="display:flex;flex-direction:column;gap:1rem">
          <div class="data-card" style="margin:0">
            <div class="data-card-head"><h3><i class="bi bi-piggy-bank" style="color:var(--forest);margin-right:5px"></i>Vos soldes actuels</h3></div>
            <div style="padding:.75rem 1.1rem;display:flex;flex-direction:column;gap:.75rem">
              <?php foreach ($soldes as $solde): ?>
                <?php
                  $attribues = (int) ($solde['jours_attribues'] ?? 0);
                  $pris = (int) ($solde['jours_pris'] ?? 0);
                  $restants = max(0, $attribues - $pris);
                  $taux = $attribues > 0 ? (int) round(($restants / $attribues) * 100) : 0;
                ?>
                <div>
                  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                    <span style="font-size:.8rem;color:var(--ink)"><?= esc($solde['type_nom'] ?? 'Congé') ?></span>
                    <span style="font-family:'DM Mono',monospace;font-size:.8rem;color:var(--forest);font-weight:500"><?= $restants ?> j</span>
                  </div>
                  <div class="solde-bar"><div class="solde-fill <?= $taux <= 20 ? 'warn' : '' ?>" style="width:<?= $taux ?>%"></div></div>
                </div>
              <?php endforeach; ?>
              <?php if (empty($soldes)): ?>
                <div class="empty" style="padding:1rem 0">
                  <i class="bi bi-inbox"></i>
                  <p>Aucun solde trouvé pour cette année.</p>
                </div>
              <?php endif; ?>
            </div>
          </div>
          <div class="flash flash-info" style="margin:0">
            <i class="bi bi-info-circle-fill"></i>
            <span style="font-size:.8rem">Le solde est déduit uniquement à l'approbation de votre responsable.</span>
          </div>
          <div style="background:var(--cream);border:1px solid var(--border);border-radius:8px;padding:.85rem 1rem">
            <div style="font-size:.78rem;font-weight:500;color:var(--ink);margin-bottom:.5rem"><i class="bi bi-clipboard-check" style="color:var(--forest);margin-right:5px"></i>Rappel des règles</div>
            <ul style="margin:0;padding-left:1rem;font-size:.75rem;color:var(--muted);line-height:1.7">
              <li>Préavis minimum : 48h avant la date de début</li>
              <li>Pas de chevauchement avec une demande en cours</li>
              <li>Solde insuffisant = demande refusée automatiquement</li>
            </ul>
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