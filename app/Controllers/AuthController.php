<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\DiretorModel;
use App\Models\UsuarioModel;

class AuthController extends BaseController
{
    public function autenticar()
    {
        $cpf   = $this->request->getPost('cpf');
        $senha = $this->request->getPost('senha');

        // 🔹 Admin
        $adminModel = new AdminModel();
        $admin = $adminModel->where('cpf', $cpf)->first();

        if ($admin && password_verify($senha, $admin['senha_hash'])) {
            $data = [
                'id'        => $admin['id'],
                'nome'      => $admin['nome'],
                'cpf'       => $admin['cpf'],
                'email'     => $admin['email'],
                'unidade_id' => $admin['unidade_id'] ?? null,
                'tipo'      => 'admin'
            ];
            session()->set('usuarioLogado', $data);
            return $this->response->setJSON(['success' => true, 'user' => $data]);
        }

        // 🔹 Diretor
        $diretorModel = new DiretorModel();
        $diretor = $diretorModel->where('cpf', $cpf)->first();

        if ($diretor && password_verify($senha, $diretor['senha_hash'])) {
            $data = [
                'id'        => $diretor['id'],
                'nome'      => $diretor['nome'],
                'cpf'       => $diretor['cpf'],
                'email'     => $diretor['email'],
                'unidade_id' => $diretor['unidade_id'],
                'tipo'      => 'diretor'
            ];
            session()->set('usuarioLogado', $data);
            return $this->response->setJSON(['success' => true, 'user' => $data]);
        }

        // 🔹 Usuário
        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->where('cpf', $cpf)->first();

        if ($usuario && password_verify($senha, $usuario['senha_hash'])) {
            $data = [
                'id'        => $usuario['id'],
                'nome'      => $usuario['nome'],
                'cpf'       => $usuario['cpf'],
                'email'     => $usuario['email'],
                'unidade_id' => $usuario['unidade_id'],
                'tipo'      => 'usuario'
            ];
            session()->set('usuarioLogado', $data);
            return $this->response->setJSON(['success' => true, 'user' => $data]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'CPF ou senha inválidos']);
    }

    public function sair()
    {
        session()->destroy();
        return $this->response->setJSON(['success' => true, 'message' => 'Logout realizado']);
    }
}
