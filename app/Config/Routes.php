<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('login', 'AuthController::index');
$routes->post('login/process', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Home::index');

    $routes->get('lang/(:segment)', 'Language::switch/$1');

    // Settings Routes
    $routes->get('settings', 'Settings::index');
    $routes->post('settings/save', 'Settings::save');
    $routes->get('settings/get-default-db', 'Settings::getDefaultDb');

    // Users Routes
    $routes->get('users', 'Users::index');
    $routes->get('users/create', 'Users::create');
    $routes->post('users/save', 'Users::save');
    $routes->get('users/edit/(:num)', 'Users::edit/$1');
    $routes->post('users/update/(:num)', 'Users::update/$1');
    $routes->get('users/delete/(:num)', 'Users::delete/$1');

    // Applications Routes
    $routes->get('applications', 'Applications::index');
    $routes->get('applications/create', 'Applications::create');
    $routes->post('applications/save', 'Applications::save');
    $routes->get('applications/edit/(:num)', 'Applications::edit/$1');
    $routes->post('applications/update/(:num)', 'Applications::update/$1');
    $routes->get('applications/delete/(:num)', 'Applications::delete/$1');
    
    // Tasks Routes (inside applications)
    $routes->get('applications/tasks/(:num)', 'Applications::tasks/$1');
    $routes->get('applications/tasks/create/(:num)', 'Applications::createTask/$1');
    $routes->post('applications/tasks/save/(:num)', 'Applications::saveTask/$1');
    $routes->get('applications/tasks/edit/(:num)', 'Applications::editTask/$1');
    $routes->post('applications/tasks/update/(:num)', 'Applications::updateTask/$1');
    $routes->get('applications/tasks/run/(:num)', 'Applications::runTask/$1');
    $routes->post('applications/tasks/test-query/(:num)', 'Applications::testQuery/$1');
    $routes->post('applications/tasks/test-php/(:num)', 'Applications::testPhp/$1');
    $routes->get('applications/tasks/delete/(:num)', 'Applications::deleteTask/$1');
});
