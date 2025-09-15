<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class AuthController extends BaseController
{
    public function login()
    {
        $data = $this->request->getJSON(true);
        $email = $data['email'] ?? null;
        $senha = $data['senha'] ?? null;

        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->where('email', $email)->first();

        if (!$usuario || !password_verify($senha, $usuario['senha'])) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Credenciais inválidas']);
        }

        // Exemplo simples de token (depois pode trocar por JWT)
        $token = bin2hex(random_bytes(16));

        return $this->response->setJSON([
            'token' => $token,
            'role' => $usuario['role'], // usuario | admin | diretor
            'unidade_id' => $usuario['unidade_id']
        ]);
    }

    public function logout()
    {
        return $this->response->setJSON(['message' => 'Logout realizado']);
    }
}
