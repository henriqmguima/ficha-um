<?php
// Ajustado: Login.php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\PostoModel;

class Login extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function autenticar()
    {
        $cpf   = $this->request->getPost('cpf');
        $senha = $this->request->getPost('senha');

        $model   = new UsuarioModel();
        $usuario = $model->where('cpf', $cpf)->first();

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // Setar somente os dados essenciais na sessão
            session()->set('usuarioLogado', [
                'id'        => $usuario['id'],
                'nome'      => $usuario['nome'],
                'cpf'       => $usuario['cpf'],     
                'is_admin'  => $usuario['is_admin'],
                'posto_id'  => $usuario['posto_id'],
            ]);

            if ($usuario['is_admin']) {
                return redirect()->to('/painel');
            } else {
                return redirect()->to('/users');
            }
        }

        return redirect()->back()->with('erro', 'CPF ou senha inválidos');
    }

    public function sair()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
