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
    <section id="page-admin-employes" style="margin-top:3rem">
<?php
$employees = $employees ?? [];
?>
<div class="app-wrap">

  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon" style="background:var(--ink);border:1px solid rgba(255,255,255,.15)"><i class="bi bi-shield-check" style="color:var(--leaf)"></i></div>
      <div class="sidebar-brand-name">TechMada RH<span>Administration</span></div>
    </div>
    <ul class="sidebar-nav" style="margin-top:1rem">
      <li><a href="<?= site_url('admin/dashboard') ?>"><i class="bi bi-speedometer2"></i> Vue d'ensemble</a></li>
      <li><a href="<?= site_url('rh/demandes') ?>"><i class="bi bi-inbox"></i> Toutes les demandes</a></li>
      <li><a href="<?= site_url('admin/employes') ?>" class="active"><i class="bi bi-people"></i> Employés</a></li>
      <li><a href="<?= site_url('admin/employes') ?>"><i class="bi bi-building"></i> Départements</a></li>
      <li><a href="<?= site_url('admin/employes') ?>"><i class="bi bi-tags"></i> Types de congé</a></li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar" style="background:#5a2d82;width:32px;height:32px;font-size:.7rem">AD</div>
        <div><div class="user-name">Administrateur</div><div class="user-role">Admin système</div></div>
      </div>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Gestion des employés</div>
        <div class="topbar-breadcrumb"><a href="<?= site_url('admin/dashboard') ?>">Admin</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Employés</div>
      </div>
      <div class="topbar-actions">
        <a href="<?= site_url('admin/employes') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-person-plus"></i> Ajouter</a>
      </div>
    </div>

    <div class="content">

      <!-- Formulaire ajout -->
      <form class="form-section" action="<?= site_url('admin/employes') ?>" method="post">
        <?= csrf_field() ?>
        <h3><i class="bi bi-person-plus" style="color:var(--forest);margin-right:6px"></i>Ajouter un employé</h3>
        <div class="form-grid-2" style="margin-bottom:1rem">
          <div class="f-group">
            <label class="f-label">Prénom</label>
            <input type="text" name="prenom" class="f-input" placeholder="Jean"/>
          </div>
          <div class="f-group">
            <label class="f-label">Nom</label>
            <input type="text" name="nom" class="f-input" placeholder="Rakoto"/>
          </div>
          <div class="f-group">
            <label class="f-label">Email</label>
            <input type="email" name="email" class="f-input" placeholder="jean.rakoto@techmada.mg"/>
          </div>
          <div class="f-group">
            <label class="f-label">Mot de passe initial</label>
            <input type="password" name="password" class="f-input" placeholder="À communiquer à l'employé"/>
          </div>
          <div class="f-group">
            <label class="f-label">Département</label>
            <select class="f-select" name="departement_id" required>
              <option value="">-- Choisir un département --</option>
              <?php foreach (($departements ?? []) as $departement): ?>
                <option value="<?= esc($departement['id']) ?>">
                  <?= esc($departement['libelle'] ?? $departement['nom'] ?? 'Département') ?>
                </option>
              <?php endforeach; ?>
              <?php if (empty($departements ?? [])): ?>
                <option value="" disabled>Aucun département disponible</option>
              <?php endif; ?>
            </select>
          </div>
          <div class="f-group">
            <label class="f-label">Rôle</label>
            <select class="f-select" name="role">
              <option value="employe">Employé</option>
              <option value="rh">Responsable RH</option>
              <option value="admin">Administrateur</option>
            </select>
          </div>
          <div class="f-group">
            <label class="f-label">Date d'embauche</label>
            <input type="date" name="date_embauche" class="f-input" value="2025-06-13"/>
          </div>
        </div>
        <div class="flash flash-info" style="margin-bottom:1rem">
          <i class="bi bi-info-circle-fill"></i>
          <span style="font-size:.82rem">Les soldes de congés seront initialisés automatiquement selon les types de congé configurés.</span>
        </div>
        <div class="form-actions">
          <button class="btn-forest" type="submit"><i class="bi bi-plus"></i> Créer l'employé</button>
          <button class="btn-secondary">Réinitialiser</button>
        </div>
      </form>

      <!-- Liste employés -->
      <div class="data-card">
        <div class="data-card-head">
          <h3>Tous les employés</h3>
          <div style="display:flex;gap:6px">
            <input type="text" class="f-input" placeholder="Rechercher..." style="width:200px;padding:6px 10px;font-size:.8rem"/>
            <select class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto">
              <option>Tous les depts</option>
              <option>IT</option>
              <option>Finance</option>
            </select>
          </div>
        </div>
        <table class="tbl">
          <thead>
            <tr><th>Employé</th><th>Département</th><th>Rôle</th><th>Embauche</th><th>Statut</th><th>Solde annuel</th><th>Actions</th></tr>
          </thead>
          <tbody>
            <?php foreach ($employees as $employee): ?>
              <?php $isActive = (int) ($employee['actif'] ?? 0) === 1; ?>
              <tr<?= $isActive ? '' : ' style="opacity:.5"' ?>>
                <td>
                  <div class="profile-row">
                    <div class="avatar av-green" style="width:32px;height:32px;font-size:.68rem"><?= esc(substr(($employee['prenom'] ?? '') . ' ' . ($employee['nom'] ?? ''), 0, 2)) ?></div>
                    <div class="profile-info"><div class="pname"><?= esc(trim(($employee['prenom'] ?? '') . ' ' . ($employee['nom'] ?? ''))) ?></div><div class="pdept"><?= esc($employee['email'] ?? '-') ?></div></div>
                  </div>
                </td>
                <td class="td-muted"><?= esc($employee['departement_nom'] ?? '-') ?></td>
                <td><span class="type-badge" style="background:#f1efe8;color:#444441"><?= esc($employee['role'] ?? '-') ?></span></td>
                <td class="td-muted td-mono" style="font-size:.78rem"><?= esc($employee['date_embauche'] ?? '-') ?></td>
                <td><span class="statut <?= $isActive ? 's-approuvee' : 's-annulee' ?>" style="font-size:.68rem"><?= $isActive ? 'actif' : 'inactif' ?></span></td>
                <td><span style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--forest)">—</span></td>
                <td>
                  <div class="action-btns">
                    <button class="btn-sm btn-edit" type="button"><i class="bi bi-pencil"></i> Éditer</button>
                    <?php if ($isActive): ?>
                      <form action="<?= site_url('admin/employes/' . ($employee['id'] ?? 0) . '/delete') ?>" method="post" style="display:inline">
                        <?= csrf_field() ?>
                        <button class="btn-sm btn-del" type="submit"><i class="bi bi-slash-circle"></i></button>
                      </form>
                    <?php else: ?>
                      <form action="<?= site_url('admin/employes/' . ($employee['id'] ?? 0) . '/reactiver') ?>" method="post" style="display:inline">
                        <?= csrf_field() ?>
                        <button class="btn-sm btn-view" type="submit"><i class="bi bi-arrow-counterclockwise"></i> Réactiver</button>
                      </form>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($employees)): ?>
              <tr><td colspan="7"><div class="empty"><i class="bi bi-inbox"></i><p>Aucun employé trouvé dans la base.</p></div></td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>

</div>
</section>


<!-- Navigation demo interne -->
<script>
document.querySelectorAll('a[href^="#"]').forEach(a=>{
  a.addEventListener('click',e=>{
    const t=document.querySelector(a.getAttribute('href'));
    if(t){e.preventDefault();t.scrollIntoView({behavior:'smooth',block:'start'})}
  });
});
</script>
</body>
</html>