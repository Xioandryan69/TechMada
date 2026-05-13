<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =============================================
// Routes application
// =============================================

$routes->get('/', 'Home::index');
$routes->get('login', 'AuthController::loginForm');
$routes->post('login', 'AuthController::login');
$routes->get('dashboard', 'AuthController::dashboard', ['filter' => 'auth']);
$routes->get('logout', 'AuthController::logout');




// --- Routes Congés et employés ---
$routes->group('employee', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'EmployeeController::dashboard');
    $routes->get('conges', 'EmployeeController::demandes');
    $routes->get('conges/create', 'EmployeeController::formulaireDemande');
    $routes->get('profil', 'EmployeeController::profil');
    $routes->post('conges', 'EmployeeController::storeDemande');
    $routes->post('conges/(:num)/annuler', 'EmployeeController::annulerDemande/$1');
});

$routes->group('rh', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'RhController::dashboardAdmin');
    $routes->get('demandes', 'RhController::demandes');
    $routes->get('historique', 'RhController::demandes');
    $routes->get('soldes', 'RhController::demandes');
    $routes->post('demandes/(:num)/valider', 'RhController::valider/$1');
    $routes->post('demandes/(:num)/refuser', 'RhController::refuser/$1');
});

$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'RhController::dashboardAdmin');
    $routes->get('employes', 'EmployeeController::gestion');
    $routes->get('departements', 'EmployeeController::gestion');
    $routes->get('types-conges', 'EmployeeController::gestion');
    $routes->get('soldes-annuels', 'EmployeeController::gestion');
    $routes->post('employes', 'EmployeeController::store');
    $routes->post('employes/(:num)', 'EmployeeController::update/$1');
    $routes->post('employes/(:num)/delete', 'EmployeeController::delete/$1');
    $routes->post('employes/(:num)/reactiver', 'EmployeeController::reactiver/$1');
});

