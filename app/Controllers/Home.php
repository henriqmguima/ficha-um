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

        if ($usuario['is_admin']) {
            return redirect()->to('/painel');
        }

        return redirect()->to('/users');
    }
}
