<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $usuario = session()->get('usuarioLogado');

        if (!$usuario) {
            return redirect()->to('/login');
        }

        // Redireciona com base no papel do usuário
        switch ($usuario['role']) {
            case 'admin':
                return redirect()->to('/painel');
            case 'diretor':
                return redirect()->to('/admin/fichas');
            case 'medico':
                return redirect()->to('/medico');
            case 'usuario':
            default:
                return redirect()->to('/users');
        }
    }
}
