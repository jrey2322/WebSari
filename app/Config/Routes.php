<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('/', 'Auth::login');

// Auth
$routes->get( 'login',    'Auth::login');
$routes->post('login',    'Auth::authenticate');
$routes->get( 'register', 'Auth::register');
$routes->post('register', 'Auth::registerStore');
$routes->get( 'logout',   'Auth::logout');

// Dashboard
$routes->get('dashboard', 'Dashboard::index');

// Products
$routes->get( 'products',               'Products::index');
$routes->get( 'products/create',        'Products::create');
$routes->post('products/store',         'Products::store');
$routes->get( 'products/edit/(:num)',   'Products::edit/$1');
$routes->post('products/update/(:num)', 'Products::update/$1');
$routes->get( 'products/delete/(:num)', 'Products::delete/$1');
$routes->get( 'products/low-stock',    'Products::lowStock');
$routes->get( 'products/search',       'Products::search');

// Sales
$routes->get( 'sales',                'Sales::index');
$routes->get( 'sales/create',         'Sales::create');
$routes->post('sales/store',          'Sales::store');   
$routes->get( 'sales/view/(:num)',    'Sales::view/$1');
$routes->get( 'sales/invoice/(:num)', 'Sales::invoice/$1');
$routes->get( 'sales/void/(:num)',    'Sales::void/$1');

// Categories
$routes->get( 'categories',               'Categories::index');
$routes->post('categories/store',         'Categories::store');
$routes->post('categories/update/(:num)', 'Categories::update/$1');
$routes->get( 'categories/delete/(:num)', 'Categories::delete/$1');

// Reports
$routes->get('reports',           'Reports::index');
$routes->get('reports/sales',     'Reports::sales');
$routes->get('reports/inventory', 'Reports::inventory');

// Users
$routes->get( 'users',               'Users::index');
$routes->post('users/store',         'Users::store');
$routes->get( 'users/toggle/(:num)', 'Users::toggle/$1');
$routes->get( 'users/delete/(:num)', 'Users::delete/$1');

// Products
$routes->post('products/restock', 'Products::restock');

// Add these utang routes
$routes->get( 'utang',                  'Utang::index');
$routes->get( 'utang/view/(:num)',      'Utang::view/$1');
$routes->post('utang/pay/(:num)',       'Utang::pay/$1');
$routes->get( 'utang/markpaid/(:num)', 'Utang::markPaid/$1');
