<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('fila', 'Fila::index');
$routes->post('fila/resultado', 'Fila::resultado');
$routes->get('login', 'Login::index');
$routes->post('login/autenticar', 'Login::autenticar');
$routes->get('logout', 'Login::sair');

// área protegida (pode mudar depois)
$routes->get('painel', 'Admin\FichaController::index'); 

$routes->group('api', function($routes) {
    $routes->post('fichas', 'Api\FichaApi::create');
    $routes->get('fichas/minha-ficha', 'Api\FichaApi::minhaFicha');
});

$routes->group('admin', ['filter' => 'adminauth'], function($routes) {
    $routes->get('fichas', 'Admin\FichaController::index');
    $routes->get('fichas/create', 'Admin\FichaController::create');
    $routes->post('fichas/store', 'Admin\FichaController::store');
    $routes->get('fichas/status/(:num)/(:segment)', 'Admin\FichaController::updateStatus/$1/$2');
    $routes->get('fichas/delete/(:num)', 'Admin\FichaController::delete/$1');

    $routes->get('usuarios/create', 'Admin\UsuarioController::create');
    $routes->post('usuarios/store', 'Admin\UsuarioController::store');
});
