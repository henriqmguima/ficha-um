<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class UsuarioController extends BaseController
{
    public function store()
    {
        helper(['text', 'form']);
        $usuarioModel = new UsuarioModel();

        $usuarioLogado = session()->get('usuarioLogado');

        // Só Admin ou Diretor pode cadastrar novos usuários
        if (!$usuarioLogado || !isset($usuarioLogado['tipo']) || !in_array($usuarioLogado['tipo'], ['admin', 'diretor'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Acesso negado. Apenas administradores ou diretores podem cadastrar usuários.'
            ]);
        }

        if (!isset($usuarioLogado['unidade_id'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unidade não identificada na sessão.'
            ]);
        }

        // Gera senha forte
        $senhaPura = $this->gerarSenhaForte(8);
        $senhaHash = password_hash($senhaPura, PASSWORD_DEFAULT);

        $data = [
            'cpf'                  => $this->request->getPost('cpf'),
            'nome'                 => $this->request->getPost('nome'),
            'cartao_sus'           => $this->request->getPost('cartao_sus'),
            'endereco'             => $this->request->getPost('endereco'),
            'telefone'             => $this->request->getPost('telefone'),
            'email'                => $this->request->getPost('email'),
            'senha_hash'           => $senhaHash,
            'unidade_id'           => $usuarioLogado['unidade_id'], // unidade do admin/diretor
            'autenticacao_status'  => 'pendente',
            'created_at'           => date('Y-m-d H:i:s'),
        ];

        if (!$usuarioModel->insert($data)) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => $usuarioModel->errors()
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'senha'   => $senhaPura
        ]);
    }
}
