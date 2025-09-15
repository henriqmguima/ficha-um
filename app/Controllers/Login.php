<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\DiretorModel;
use App\Models\UsuarioModel;

class Login extends BaseController
{
    public function index()
    {
        return view('auth/login');
    }

    public function autenticar()
    {
        $cpf   = $this->request->getPost('cpf');
        $senha = $this->request->getPost('senha');

        // 🔹 Admin
        $adminModel = new AdminModel();
        $admin = $adminModel->where('cpf', $cpf)->first();

        if ($admin && password_verify($senha, $admin['senha_hash'])) {
            session()->set('usuarioLogado', [
                'id'        => $admin['id'],
                'nome'      => $admin['nome'],
                'cpf'       => $admin['cpf'],
                'email'     => $admin['email'],
                'unidade_id' => $admin['unidade_id'] ?? null,
                'tipo'      => 'admin'
            ]);
            return redirect()->to('/painel');
        }

        // 🔹 Diretor
        $diretorModel = new DiretorModel();
        $diretor = $diretorModel->where('cpf', $cpf)->first();

        if ($diretor && password_verify($senha, $diretor['senha_hash'])) {
            session()->set('usuarioLogado', [
                'id'        => $diretor['id'],
                'nome'      => $diretor['nome'],
                'cpf'       => $diretor['cpf'],
                'email'     => $diretor['email'],
                'unidade_id' => $diretor['unidade_id'],
                'tipo'      => 'diretor'
            ]);
            return redirect()->to('/painel');
        }

        // 🔹 Usuário comum
        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->where('cpf', $cpf)->first();

        if ($usuario && password_verify($senha, $usuario['senha_hash'])) {
            session()->set('usuarioLogado', [
                'id'        => $usuario['id'],
                'nome'      => $usuario['nome'],
                'cpf'       => $usuario['cpf'],
                'email'     => $usuario['email'],
                'unidade_id' => $usuario['unidade_id'],
                'tipo'      => 'usuario'
            ]);
            return redirect()->to('/users');
        }

        // Falha
        return redirect()->back()->with('erro', 'CPF ou senha inválidos');
    }

    public function sair()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
