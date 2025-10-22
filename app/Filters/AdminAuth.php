<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $usuario = session()->get('usuarioLogado');

        // Verifica se está logado e se tem papel autorizado
        if (
            !is_array($usuario) ||
            !isset($usuario['role']) ||
            !in_array($usuario['role'], ['admin', 'diretor'])
        ) {
            // Redireciona para login, não para "fila"
            return redirect()->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nenhuma ação após a execução
    }
}
