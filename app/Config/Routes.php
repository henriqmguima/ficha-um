<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Página inicial com escolha: login ou registrar posto
$routes->get('/', 'Home::index');

// Rotas para login e logout
$routes->get('login', 'Login::index');
$routes->post('login/autenticar', 'Login::autenticar');
$routes->get('logout', 'Login::sair');

// Registro de novos postos
$routes->get('registrar-posto', 'PostoController::create');
$routes->post('registrar-posto', 'PostoController::store');

// Página do usuário comum
$routes->get('users', 'Usuario::index');

// Painel principal do administrador (diretor ou admin)
$routes->get('painel', 'Admin\FichaController::index');

// Painel do médico
$routes->get('/medico', 'MedicoController::index');
$routes->get('/medico/assumir/(:num)', 'MedicoController::assumirFicha/$1');
$routes->get('/medico/finalizar/(:num)', 'MedicoController::finalizarFicha/$1');
$routes->get('/medico/ver/(:num)', 'MedicoController::verFicha/$1');
$routes->get('medico/api/fichas', 'MedicoController::apiFichas');

// Rotas de API
$routes->group('api', function ($routes) {
    $routes->post('fichas', 'Api\FichaApi::create');
    $routes->get('fichas/minha-ficha', 'Api\FichaApi::minhaFicha');
    $routes->get('fichas/listar', 'Api\FichaApi::listar');
});

// Área protegida para administradores de postos
$routes->group('admin', ['filter' => 'adminauth'], function ($routes) {
    // Fichas
    $routes->get('fichas', 'Admin\FichaController::index');
    $routes->get('fichas/create', 'Admin\FichaController::create');
    $routes->post('fichas/store', 'Admin\FichaController::store');
    $routes->get('fichas/status/(:num)/(:segment)', 'Admin\FichaController::updateStatus/$1/$2');
    $routes->get('fichas/delete/(:num)', 'Admin\FichaController::delete/$1');
    $routes->get('fichas/avaliar/(:num)', 'Admin\FichaController::avaliar/$1');
    $routes->post('fichas/salvarAvaliacao/(:num)', 'Admin\FichaController::salvarAvaliacao/$1');

    // Usuários
    $routes->get('usuarios/create', 'Admin\UsuarioController::create');
    $routes->post('usuarios/store', 'Admin\UsuarioController::store');
});
