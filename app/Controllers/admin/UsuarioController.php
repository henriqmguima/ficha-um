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

        $senhaPura = random_string('alnum', 8);
        $senhaHash = password_hash($senhaPura, PASSWORD_DEFAULT);

        $salvo = $model->insert([
            'cpf'        => $this->request->getPost('cpf'),
            'nome'       => $this->request->getPost('nome'),
            'cartao_sus' => $this->request->getPost('cartao_sus'),
            'endereco'   => $this->request->getPost('endereco'),
            'email'      => $this->request->getPost('email'),
            'senha'      => $senhaHash,
            'is_admin'   => $this->request->getPost('is_admin') ? 1 : 0,
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
            'senha' => $senhaPura
        ]);
    }


}
