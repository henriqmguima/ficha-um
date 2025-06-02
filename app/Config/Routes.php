<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('admin/fichas', 'Admin\FichaController::index');
$routes->get('admin/fichas/create', 'Admin\FichaController::create');
$routes->post('admin/fichas/store', 'Admin\FichaController::store');
$routes->get('admin/fichas/status/(:num)/(:segment)', 'Admin\FichaController::updateStatus/$1/$2');
$routes->get('admin/fichas/delete/(:num)', 'Admin\FichaController::delete/$1');
