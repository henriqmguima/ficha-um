<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class UsuariosController extends BaseController
{
    public function create()
    {
        $data = $this->request->getJSON(true);

        $usuarioLogado = session()->get('usuarioLogado');

        if (!$usuarioLogado || !isset($usuarioLogado['unidade_id'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'Unidade não identificada para este cadastro.'
            ]);
        }

        $data['unidade_id'] = $usuarioLogado['unidade_id'];
        $data['autenticacao_status'] = 'pendente';
        $data['created_at'] = date('Y-m-d H:i:s');

        $usuarioModel = new UsuarioModel();

        if (!$usuarioModel->insert($data)) {
            return $this->response->setStatusCode(400)->setJSON(['errors' => $usuarioModel->errors()]);
        }

        return $this->response->setJSON(['message' => 'Usuário registrado com sucesso']);
    }


    public function show($id)
    {
        $usuario = (new UsuarioModel())->find($id);
        if (!$usuario) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Usuário não encontrado']);
        }
        return $this->response->setJSON($usuario);
    }

    public function aprovar($id)
    {
        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->find($id);

        if (!$usuario) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Usuário não encontrado']);
        }

        $usuarioModel->update($id, ['autenticado' => true]);

        return $this->response->setJSON(['message' => 'Usuário aprovado para uso remoto']);
    }
}
