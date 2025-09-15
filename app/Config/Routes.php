<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Página inicial redireciona para login
$routes->get('/', 'Login::index');

// Rotas de autenticação
$routes->get('login', 'Login::index');
$routes->post('login/autenticar', 'Login::autenticar');
$routes->get('logout', 'Login::sair');

// Registro de novas unidades
$routes->get('registrar-unidade', 'UnidadeController::create');
$routes->post('registrar-unidade', 'UnidadeController::store');

// Registro de usuários (fluxo público básico)
$routes->get('registrar-usuario', 'Usuario::create');
$routes->post('registrar-usuario', 'Usuario::store');

// Página inicial do usuário comum
$routes->get('users', 'UsuarioController::index');

// Painel principal (administradores/diretores)
$routes->get('painel', 'Admin\FichaController::index');

// // Rotas API simples
// $routes->group('api', function ($routes) {
//     $routes->post('fichas', 'Api\FichaApi::create');
//     $routes->get('fichas/minha-ficha', 'Api\FichaApi::minhaFicha');
//     $routes->get('fichas/listar', 'Api\FichaApi::listar');
// });

// Área protegida para administradores/diretores
$routes->group('admin', ['filter' => 'adminauth'], function ($routes) {
    // Fichas
    $routes->get('fichas', 'Admin\FichaController::index');
    $routes->get('fichas/create', 'Admin\FichaController::create');
    $routes->post('fichas/store', 'Admin\FichaController::store');
    $routes->get('fichas/status/(:num)/(:segment)', 'Admin\FichaController::updateStatus/$1/$2');
    $routes->get('fichas/delete/(:num)', 'Admin\FichaController::delete/$1');

    // Usuários
    $routes->get('usuarios/create', 'Admin\UsuarioController::create');
    $routes->post('usuarios/store', 'Admin\UsuarioController::store');
});

// API v1
$routes->group('api/v1', ['namespace' => 'App\Controllers\Api'], function ($routes) {

    // Autenticação (API)
    $routes->post('auth/login', 'AuthController::login');
    $routes->post('auth/logout', 'AuthController::logout');

    // Catálogos públicos
    $routes->get('unidades', 'UnidadesController::index');
    $routes->get('unidades/(:num)', 'UnidadesController::show/$1');
    $routes->get('unidades/(:num)/horarios', 'UnidadesController::horarios/$1');
    $routes->get('tipos-atendimento', 'TiposAtendimentoController::index');

    // Registro remoto de usuários
    $routes->post('usuarios', 'UsuariosController::create');

    // Admin/Diretor (com filtro de autenticação)
    $routes->group('', ['filter' => 'auth:admin_or_diretor'], function ($routes) {
        $routes->patch('usuarios/(:num)/aprovar', 'UsuariosController::aprovar/$1');

        // Fichas
        $routes->resource('fichas', ['controller' => 'FichasController', 'only' => ['index', 'show', 'create']]);
        $routes->get('fichas/fila/triagem', 'FichasController::filaTriagem');
        $routes->get('fichas/fila/atendimento', 'FichasController::filaAtendimento');

        // Transições de status
        $routes->patch('fichas/(:num)/autenticar', 'FichasController::autenticar/$1');
        $routes->patch('fichas/(:num)/iniciar-triagem', 'FichasController::iniciarTriagem/$1');
        $routes->patch('fichas/(:num)/finalizar-triagem', 'FichasController::finalizarTriagem/$1');
        $routes->patch('fichas/(:num)/iniciar-atendimento', 'FichasController::iniciarAtendimento/$1');
        $routes->patch('fichas/(:num)/finalizar-atendimento', 'FichasController::finalizarAtendimento/$1');
        $routes->patch('fichas/(:num)/cancelar', 'FichasController::cancelar/$1');
        $routes->patch('fichas/(:num)/no-show', 'FichasController::noShow/$1');
    });

    // Usuário autenticado comum
    $routes->group('', ['filter' => 'auth:usuario'], function ($routes) {
        $routes->get('minha-ficha', 'FichasController::minhaFicha');
        $routes->post('fichas', 'FichasController::create');
    });
});
