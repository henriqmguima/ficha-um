<?php

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

        // Verifica se o usuário existe e se a senha confere
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // Define dados essenciais na sessão
            session()->set('usuarioLogado', [
                'id'       => $usuario['id'],
                'nome'     => $usuario['nome'],
                'cpf'      => $usuario['cpf'],
                'role'     => $usuario['role'],
                'posto_id' => $usuario['posto_id'],
            ]);

            // Redireciona conforme o papel (role)
            // Redireciona conforme o papel (role)
            switch ($usuario['role']) {
                case 'admin':
                case 'diretor':
                    return redirect()->to('/painel'); // painel administrativo
                    break;

                case 'medico':
                    return redirect()->to('/medico'); // painel do médico
                    break;

                case 'usuario':
                default:
                    return redirect()->to('/users'); // página pública
                    break;
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
