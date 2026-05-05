<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/', 'Auth::login');

// ── Auth ──────────────────────────────────
$routes->get( '/login',    'Auth::login');
$routes->post('/login',    'Auth::authenticate');
$routes->get( '/register', 'Auth::register');
$routes->post('/register', 'Auth::registerStore');
$routes->get( '/logout',   'Auth::logout');

// ── Dashboard ────────────────────────────
$routes->get('/dashboard', 'Dashboard::index');

// ── Products ─────────────────────────────
$routes->group('products', function($routes) {
    $routes->get('/',               'Products::index');
    $routes->get('create',          'Products::create');
    $routes->post('store',          'Products::store');
    $routes->get('edit/(:num)',     'Products::edit/$1');
    $routes->post('update/(:num)',  'Products::update/$1');
    $routes->get('delete/(:num)',   'Products::delete/$1');
    $routes->get('low-stock',       'Products::lowStock');
    $routes->get('search',          'Products::search');
});

// ── Sales ────────────────────────────────
$routes->group('sales', function($routes) {
    $routes->get('/',              'Sales::index');
    $routes->get('create',        'Sales::create');
    $routes->post('store',        'Sales::store');
    $routes->get('view/(:num)',   'Sales::view/$1');
    $routes->get('invoice/(:num)','Sales::invoice/$1');
    $routes->get('void/(:num)',   'Sales::void/$1');
});

// ── Categories ───────────────────────────
$routes->group('categories', function($routes) {
    $routes->get('/',              'Categories::index');
    $routes->post('store',         'Categories::store');
    $routes->post('update/(:num)', 'Categories::update/$1');
    $routes->get('delete/(:num)',  'Categories::delete/$1');
});

// ── Reports ──────────────────────────────
$routes->group('reports', function($routes) {
    $routes->get('/',          'Reports::index');
    $routes->get('sales',      'Reports::sales');
    $routes->get('inventory',  'Reports::inventory');
});

// ── Users (Owner only) ───────────────────
$routes->group('users', function($routes) {
    $routes->get('/',              'Users::index');
    $routes->post('store',         'Users::store');
    $routes->get('toggle/(:num)',  'Users::toggle/$1');
    $routes->get('delete/(:num)',  'Users::delete/$1');
});
