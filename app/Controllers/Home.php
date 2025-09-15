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

        // Detecta admin/diretor/usuario por chaves existentes na sessão.
        // (quando fizer login, armazene na sessão pelo menos uma dessas chaves,
        //  por exemplo: 'id_admin', 'id_diretor' ou 'id_usuario')
        $isAdmin   = !empty($usuarioSess['id_admin'])   || !empty($usuarioSess['admin_id']);
        $isDiretor = !empty($usuarioSess['id_diretor']) || !empty($usuarioSess['diretor_id']);
        $isUsuario = !empty($usuarioSess['id_usuario']) || !empty($usuarioSess['usuario_id']);

        if ($isAdmin || $isDiretor) {
            return redirect()->to('/painel');
        }

        if ($isUsuario) {
            return redirect()->to('/users');
        }

        // fallback — não tem dados claros de que tipo de usuário é
        return redirect()->to('/login');
    }
}
