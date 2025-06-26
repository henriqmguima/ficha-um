<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PostoModel;
use App\Models\UsuarioModel;

class PostoController extends BaseController
{
    public function create()
    {
        return view('registro_posto');
    }

    public function store()
    {
        helper(['form', 'text']);

        $validation = \Config\Services::validation();
        $validation->setRules([
            'cnes'           => 'required|is_unique[postos.cnes]',
            'nome'           => 'required|min_length[3]',
            'endereco'       => 'required',
            'cep'            => 'required',
            'cidade'         => 'required',
            'estado'         => 'required',
            'pais'           => 'required',
            'diretor_nome'   => 'required',
            'diretor_email'  => 'required|valid_email|is_unique[usuarios.email]',
            'diretor_cpf'    => 'required|is_unique[usuarios.cpf]',
            'diretor_senha'  => 'required|min_length[6]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }


        // Criar posto
        $postoModel = new PostoModel();
        $postoId = $postoModel->insert([
            'cnes'     => $this->request->getPost('cnes'),
            'nome'     => $this->request->getPost('nome'),
            'endereco' => $this->request->getPost('endereco'),
            'cep'      => $this->request->getPost('cep'),
            'cidade'   => $this->request->getPost('cidade'),
            'estado'   => $this->request->getPost('estado'),
            'pais'     => $this->request->getPost('pais'),
            'criado_em' => date('Y-m-d H:i:s')
        ]);
        if (!$postoId) {
            return redirect()->back()->with('erro', 'Erro ao salvar o posto.');
        }

        // Criar diretor vinculado ao posto
        $usuarioModel = new UsuarioModel();
        $senha = $this->request->getPost('diretor_senha');
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $usuarioModel->insert([
            'nome'     => $this->request->getPost('diretor_nome'),
            'email'    => $this->request->getPost('diretor_email'),
            'cpf'      => $this->request->getPost('diretor_cpf'),
            'senha'    => $senhaHash,
            'is_admin' => 1,
            'posto_id' => $postoId,
            'criado_em' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/login')->with('success', 'Unidade registrada com sucesso. Faça login para continuar.');
    }
}
