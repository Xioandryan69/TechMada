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
    <section id="page-liste-rh" style="margin-top:3rem">
<?php
$conges = $conges ?? [];
$pendingConges = $pendingConges ?? [];
$validatedConges = array_values(array_filter($conges, static fn ($conge) => ($conge['statut'] ?? '') === 'valide'));
$refusedConges = array_values(array_filter($conges, static fn ($conge) => ($conge['statut'] ?? '') === 'refuse'));
?>
<div class="app-wrap">

  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon"><i class="bi bi-person-check"></i></div>
      <div class="sidebar-brand-name">TechMada RH<span>Espace responsable</span></div>
    </div>
    <div class="sidebar-section">Menu</div>
    <ul class="sidebar-nav">
      <li><a href="<?= site_url('rh/dashboard') ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
      <li>
        <a href="<?= site_url('rh/demandes') ?>" class="active">
          <i class="bi bi-inbox"></i> Demandes à traiter
          <span class="nav-badge alert"><?= count($pendingConges) ?></span>
        </a>
      </li>
      <li><a href="<?= site_url('rh/historique') ?>"><i class="bi bi-archive"></i> Historique</a></li>
      <li><a href="<?= site_url('rh/soldes') ?>"><i class="bi bi-people"></i> Soldes employés</a></li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar av-blue">MR</div>
        <div><div class="user-name">Marie Rabe</div><div class="user-role">Responsable RH</div></div>
        <a href="<?= site_url('logout') ?>" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem"><i class="bi bi-box-arrow-right"></i></a>
      </div>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Demandes à traiter</div>
        <div class="topbar-breadcrumb"><a href="<?= site_url('rh/dashboard') ?>">Accueil</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Demandes</div>
      </div>
      <div class="topbar-actions">
        <span style="font-size:.8rem;color:var(--muted);background:var(--warn-bg);border:1px solid var(--warn-br);border-radius:6px;padding:5px 10px;display:flex;align-items:center;gap:5px;color:var(--warn)">
          <i class="bi bi-hourglass-split"></i> <?= count($pendingConges) ?> en attente
        </span>
      </div>
    </div>

    <div class="content">

      <?php if ($successMessage = session()->getFlashdata('success')): ?>
      <div class="flash flash-success">
        <i class="bi bi-check-circle-fill"></i>
        <?= esc($successMessage) ?>
      </div>
      <?php endif; ?>

      <!-- Filtre -->
      <div style="display:flex;gap:8px;margin-bottom:1.25rem;flex-wrap:wrap">
        <button style="padding:6px 14px;border-radius:20px;font-size:.8rem;font-weight:500;border:1.5px solid var(--forest);background:var(--forest);color:var(--white);cursor:pointer">Tous (<?= count($conges) ?>)</button>
        <button style="padding:6px 14px;border-radius:20px;font-size:.8rem;font-weight:500;border:1.5px solid var(--border);background:var(--white);color:var(--muted);cursor:pointer">En attente (<?= count($pendingConges) ?>)</button>
        <button style="padding:6px 14px;border-radius:20px;font-size:.8rem;font-weight:500;border:1.5px solid var(--border);background:var(--white);color:var(--muted);cursor:pointer">Approuvées (<?= count($validatedConges) ?>)</button>
        <button style="padding:6px 14px;border-radius:20px;font-size:.8rem;font-weight:500;border:1.5px solid var(--border);background:var(--white);color:var(--muted);cursor:pointer">Refusées (<?= count($refusedConges) ?>)</button>
        <select class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto;margin-left:auto">
          <option>Tous les départements</option>
          <option>IT</option>
          <option>Finance</option>
          <option>Marketing</option>
        </select>
      </div>

      <div class="data-card">
        <div class="data-card-head"><h3>Toutes les demandes</h3></div>
        <table class="tbl">
          <thead>
            <tr><th>Employé</th><th>Type</th><th>Période</th><th>Durée</th><th>Solde dispo</th><th>Statut</th><th>Actions</th></tr>
          </thead>
          <tbody>
            <?php foreach ($conges as $conge): ?>
              <?php
                $statut = $conge['statut'] ?? '';
                $statutLabel = $statut === 'valide' ? 'approuvée' : ($statut === 'refuse' ? 'refusée' : 'en attente');
                $statusClass = $statut === 'valide' ? 's-approuvee' : ($statut === 'refuse' ? 's-refusee' : 's-attente');
                $remaining = 0;
                if (!empty($conge['employe_id']) && !empty($conge['type_conge_id'])) {
                    $remaining = (int) (new \App\Models\SoldeModel())->getRemainingDays((int) $conge['employe_id'], (int) $conge['type_conge_id'], (int) date('Y'));
                }
              ?>
              <tr>
                <td>
                  <div class="profile-row">
                    <div class="avatar av-green" style="width:32px;height:32px;font-size:.7rem"><?= esc(substr(($conge['employe_prenom'] ?? '') . ' ' . ($conge['employe_nom'] ?? ''), 0, 2)) ?></div>
                    <div class="profile-info">
                      <div class="pname"><?= esc(trim(($conge['employe_prenom'] ?? '') . ' ' . ($conge['employe_nom'] ?? ''))) ?></div>
                      <div class="pdept"><?= esc($conge['departement_nom'] ?? '-') ?></div>
                    </div>
                  </div>
                </td>
                <td><span class="type-badge t-annuel"><?= esc($conge['type_libelle'] ?? 'Congé') ?></span></td>
                <td class="td-muted" style="font-size:.8rem"><?= esc(($conge['date_debut'] ?? '-') . ' – ' . ($conge['date_fin'] ?? '-')) ?></td>
                <td class="td-mono"><?= esc((string) ($conge['nb_jours'] ?? 0)) ?> j</td>
                <td>
                  <span style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--success);font-weight:500"><?= $remaining ?> j</span>
                  <span style="font-size:.72rem;color:var(--muted)"> dispo</span>
                </td>
                <td><span class="statut <?= $statusClass ?>"><?= esc($statutLabel) ?></span></td>
                <td>
                  <div class="action-btns">
                    <?php if ($statut === 'en_attente'): ?>
                      <form action="<?= site_url('rh/demandes/' . ($conge['id'] ?? 0) . '/valider') ?>" method="post" style="display:inline">
                        <?= csrf_field() ?>
                        <button class="btn-sm btn-approve" type="submit"><i class="bi bi-check-lg"></i> Approuver</button>
                      </form>
                      <form action="<?= site_url('rh/demandes/' . ($conge['id'] ?? 0) . '/refuser') ?>" method="post" style="display:inline">
                        <?= csrf_field() ?>
                        <button class="btn-sm btn-refuse" type="submit"><i class="bi bi-x-lg"></i> Refuser</button>
                      </form>
                    <?php else: ?>
                      <span class="td-muted" style="font-size:.75rem">Traité</span>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($conges)): ?>
              <tr><td colspan="7"><div class="empty"><i class="bi bi-inbox"></i><p>Aucune demande trouvée.</p></div></td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Modal refus (inline, visible ici pour le template) -->
      <div style="margin-top:1.5rem">
        <div class="form-section" style="border-color:var(--danger-br);background:var(--danger-bg)">
          <h3 style="color:var(--danger)"><i class="bi bi-x-circle"></i> Confirmer le refus</h3>
          <div style="font-size:.875rem;color:var(--ink);margin-bottom:1rem">
            Sélectionnez une demande en attente dans le tableau ci-dessus pour la refuser.
          </div>
          <div class="f-group">
            <label class="f-label">Commentaire pour l'employé (optionnel)</label>
            <textarea class="f-textarea" placeholder="Ex : Solde insuffisant, veuillez contacter les RH pour un congé sans solde."></textarea>
          </div>
          <div class="form-actions">
            <button class="btn-sm btn-refuse" style="padding:9px 16px;font-size:.875rem"><i class="bi bi-x-lg"></i> Confirmer le refus</button>
            <button class="btn-secondary"><i class="bi bi-arrow-left"></i> Annuler</button>
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