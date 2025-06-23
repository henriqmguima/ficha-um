<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Login extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function autenticar()
    {
        $cpf = $this->request->getPost('cpf');
        $senha = $this->request->getPost('senha');

        $model = new UsuarioModel();
        $usuario = $model->where('cpf', $cpf)->first();

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            session()->set('usuarioLogado', $usuario);
            return redirect()->to('/painel');
        } else {
            return redirect()->back()->with('erro', 'CPF ou senha inválidos');
        }
        session()->set('usuarioLogado', [
            'id'       => $usuario['id'],
            'nome'     => $usuario['nome'],
            'is_admin' => $usuario['is_admin'],
        ]);

    }

    public function sair()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
