<?php
/**
 * Exemple de configuration pour chaque page
 * Copier et adapter selon vos besoins
 */

// ============================================
// 1. PAGE D'ACCUEIL (Thème Violet)
// ============================================
// File: LivreController::accueil()
$viewData = [
    'title'       => 'Accueil - Bibliothèque Numérique',
    'description' => 'Découvrez notre collection complète de livres',
    'cssFile'     => 'accueil_new.css',      // Thème violet
    'pageClass'   => 'accueil-page',
    
    // Données spécifiques
    'livres'      => $livres,
    'motCle'      => $motCle ?? '',
    'categorie_id'=> $categorie_id ?? ''
];
return view('accueil_new', $viewData);


// ============================================
// 2. PAGE DÉTAIL (Thème Vert)
// ============================================
// File: LivreController::detail($id)
$viewData = [
    'title'       => 'Détail - ' . $livre['titre'],
    'description' => 'Consultez les détails du livre: ' . $livre['titre'],
    'cssFile'     => 'detail_new.css',       // Thème vert
    'pageClass'   => 'detail-page',
    
    // Données spécifiques
    'livre'       => $livre,
    'emprunts'    => $emprunts,
    'statut'      => $statut
];
return view('detail_book', $viewData);


// ============================================
// 3. PAGE FORMULAIRE (Thème Orange)
// ============================================
// File: LivreController::ajouter()
$viewData = [
    'title'       => 'Ajouter un livre',
    'description' => 'Formulaire d\'ajout de livre',
    'cssFile'     => 'formulaire_new.css',   // Thème orange
    'pageClass'   => 'formulaire-page',
    
    // Données spécifiques
    'categories'  => $categories,
    'validation'  => $validation ?? null
];
return view('formulaire_livre', $viewData);


// ============================================
// 4. PAGE CATALOGUE (Thème Rose)
// ============================================
// File: LivreController::catalogue()
$viewData = [
    'title'       => 'Catalogue - Bibliothèque',
    'description' => 'Consultez notre catalogue complet',
    'cssFile'     => 'catalogue_new.css',    // Thème rose
    'pageClass'   => 'catalogue-page',
    
    // Données spécifiques
    'livres'      => $livres,
    'pagination'  => $pagination,
    'filtres'     => $filtres
];
return view('catalogue', $viewData);


// ============================================
// Utilisation du layout
// ============================================
// Dans chaque vue (accueil_new.php, detail_book.php, etc.):
?>

<?php $this->extend('layout/main') ?>

<?php $this->section('content') ?>

    <!-- Le layout fournit automatiquement:
         - Navigation avec le logo 📚 Bibliothèque
         - Messages flash (alert-success, alert-error, etc.)
         - Pied de page
         - Stylesheets CSS (base.css + votre cssFile)
         - Scripts JavaScript
    -->

    <!-- Votre contenu HTML ici -->

<?php $this->endSection() ?>
