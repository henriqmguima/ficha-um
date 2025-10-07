<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class UsuarioController extends BaseController
{
    public function create()
    {
        return view('admin/modal_create_usuario');
    }

    public function store()
    {
        helper('text');
        $model = new UsuarioModel();

        $usuarioLogado = session()->get('usuarioLogado');

        if (!$usuarioLogado || !isset($usuarioLogado['posto_id'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Posto não identificado na sessão.'
            ]);
        }

        // Gera senha aleatória
        $senhaPura = random_string('alnum', 8);
        $senhaHash = password_hash($senhaPura, PASSWORD_DEFAULT);

        // Determina o papel (role) do novo usuário
        // Valor vem do form, ex: 'medico', 'usuario', etc.
        $role = $this->request->getPost('role') ?? 'usuario';

        // Salva o novo usuário vinculado ao mesmo posto
        $salvo = $model->insert([
            'cpf'        => $this->request->getPost('cpf'),
            'nome'       => $this->request->getPost('nome'),
            'cartao_sus' => $this->request->getPost('cartao_sus'),
            'endereco'   => $this->request->getPost('endereco'),
            'email'      => $this->request->getPost('email'),
            'senha'      => $senhaHash,
            'role'       => $role,
            'posto_id'   => $usuarioLogado['posto_id'],
            'criado_em'  => date('Y-m-d H:i:s'),
        ]);

        if (!$salvo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao salvar usuário.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'senha'   => $senhaPura,
            'message' => 'Usuário criado com sucesso!'
        ]);
    }
}
