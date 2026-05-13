<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =============================================
// Routes application
// =============================================

$routes->get('/', 'Home::index');

// --- Routes Admin ---
$routes->get('admin/login',      'AdminAuth::login');
$routes->post('admin/loginCheck','AdminAuth::loginCheck');
$routes->get('admin/logout',     'AdminAuth::logout');
$routes->get('admin/dashboard',  'AdminDashboard::index', ['filter' => 'adminAuth']);

// CRUD Régimes (Administration protégée par le filtre adminAuth)

$routes->group('admin/regimes', ['filter' => 'adminAuth'], function($routes) {
    $routes->get('/', 'AdminRegimes::index');             // Liste
    $routes->get('create', 'AdminRegimes::create');       // Formulaire création
    $routes->post('store', 'AdminRegimes::store');        // Traitement création
    $routes->get('edit/(:num)', 'AdminRegimes::edit/$1'); // Formulaire édition
    $routes->post('update/(:num)', 'AdminRegimes::update/$1'); // Traitement édition
    $routes->get('delete/(:num)', 'AdminRegimes::delete/$1'); // Traitement suppression
});

$routes->group('admin/activites', ['filter' => 'adminAuth'], function($routes) {
    $routes->get('/', 'Adminactivites::index');             // Liste
    $routes->get('create', 'Adminactivites::create');       // Formulaire création
    $routes->post('store', 'Adminactivites::store');        // Traitement création
    $routes->get('edit/(:num)', 'Adminactivites::edit/$1'); // Formulaire édition
    $routes->post('update/(:num)', 'Adminactivites::update/$1'); // Traitement édition
    $routes->get('delete/(:num)', 'Adminactivites::delete/$1'); // Traitement suppression
});

$routes->get('admin/listUsers',  'AdminListUsers::listUsers', ['filter' => 'adminAuth']);
$routes->get('admin/profilUser/(:num)', 'AdminListUsers::affProfil/$1', ['filter' => 'adminAuth']);

// --- Routes Users : connexion ---
$routes->get('users/login',       'UsersAuth::login');
$routes->post('users/loginCheck', 'UsersAuth::loginCheck');
$routes->get('users/logout',      'UsersAuth::logout');

// --- Routes Users : inscription en 2 étapes ---
$routes->get('users/register',         'UsersAuth::register');           // Affiche étape 1
$routes->post('users/register/step1',  'UsersAuth::registerStep1');      // Traite étape 1
$routes->get('users/register/step2',   'UsersAuth::registerStep2');      // Affiche étape 2
$routes->post('users/register/step2',  'UsersAuth::registerStep2Check'); // Traite étape 2

// --- Page d'accueil connectée ---
$routes->get('users/homepage', 'UsersHomepage::index', ['filter' => 'usersAuth']);
$routes->get('users/imc', 'UsersHomepage::imc', ['filter' => 'usersAuth']);
$routes->get('users/recommendations', 'UsersHomepage::recommendations', ['filter' => 'usersAuth']);
$routes->get('users/activities/recommended', 'UsersHomepage::recommendedActivities', ['filter' => 'usersAuth']);
$routes->get('users/recommendations/exportPdf', 'UsersHomepage::exportPdf', ['filter' => 'usersAuth']);
$routes->post('users/recommendations/generate', 'UsersHomepage::generateRecommendations', ['filter' => 'usersAuth']);
$routes->post('users/updateProfile', 'UsersHomepage::updateProfile', ['filter' => 'usersAuth']);
$routes->post('users/updateObjectif', 'UsersHomepage::updateObjectif', ['filter' => 'usersAuth']);

// --- Front office: santé utilisateur ---
$routes->group('users/health', ['filter' => 'usersAuth'], function ($routes) {
    $routes->get('/', 'UserHealthController::me');
    $routes->post('save', 'UserHealthController::saveMe');
    $routes->post('calculate', 'UserHealthController::calculerIMC');
});
// --wallet
$routes->group('users', ['filter' => 'usersAuth'],function($routes){
    $routes->get('wallet/', 'WalletController::index');
    $routes->post('wallet/verify', 'WalletController::verifyCode');
    $routes->get('wallet/gold', 'GoldController::index');
    $routes->post('wallet/gold/buy', 'GoldController::buy');
});