<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class UsuarioController extends BaseController
{
    public function create()
    {
        return view('admin/usuarios/create');
    }

   public function store()
    {
        helper('text');

        $model = new UsuarioModel();

        $senhaPura = random_string('alnum', 8);
        $senhaHash = password_hash($senhaPura, PASSWORD_DEFAULT);

        $model->insert([
            'cpf'        => $this->request->getPost('cpf'),
            'nome'       => $this->request->getPost('nome'),
            'cartao_sus' => $this->request->getPost('cartao_sus'),
            'endereco'   => $this->request->getPost('endereco'),
            'email'      => $this->request->getPost('email'),
            'senha'      => $senhaHash,
            'is_admin'    => $this->request->getPost('is_admin') ? 1 : 0,
            'criado_em'  => date('Y-m-d H:i:s'),
        ]);

        session()->setFlashdata('senhaGerada', $senhaPura);

        return redirect()->to('admin/usuarios/create');
    }

}
