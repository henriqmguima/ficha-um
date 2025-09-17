<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $usuarioSess = session()->get('usuarioLogado');

        if (!$usuarioSess) {
            return redirect()->to('/login');
        }

        if (!empty($usuarioSess['tipo']) && in_array($usuarioSess['tipo'], ['admin', 'diretor'])) {
            return redirect()->to('/painel');
        }

        if (!empty($usuarioSess['tipo']) && $usuarioSess['tipo'] === 'usuario') {
            return redirect()->to('/users');
        }

        return redirect()->to('/login');
    }
}
