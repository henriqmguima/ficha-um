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

        if (!is_array($usuario) || empty($usuario['is_admin'])) {
            return redirect()->to('/fila'); // se não for admin, vai pra fila pública
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nada aqui
    }
}
