<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\FichaModel;

class FichasController extends BaseController
{
    public function index()
    {
        $fichas = (new FichaModel())->findAll();
        return $this->response->setJSON($fichas);
    }

    public function show($id)
    {
        $ficha = (new FichaModel())->find($id);
        if (!$ficha) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Ficha não encontrada']);
        }
        return $this->response->setJSON($ficha);
    }

    public function create()
    {
        $data = $this->request->getJSON(true);
        $fichaModel = new FichaModel();

        if (!$fichaModel->insert($data)) {
            return $this->response->setStatusCode(400)->setJSON(['errors' => $fichaModel->errors()]);
        }

        return $this->response->setJSON(['message' => 'Ficha criada com sucesso']);
    }

    public function filaTriagem()
    {
        $fichas = (new FichaModel())->where('status', 'aguardando')->findAll();
        return $this->response->setJSON($fichas);
    }

    public function filaAtendimento()
    {
        $fichas = (new FichaModel())->where('status', 'triagem')->findAll();
        return $this->response->setJSON($fichas);
    }

    public function minhaFicha()
    {
        $usuarioId = 1; // mock (depois extrair do token JWT ou sessão)
        $ficha = (new FichaModel())->where('usuario_id', $usuarioId)->orderBy('id_ficha', 'DESC')->first();
        return $this->response->setJSON($ficha);
    }

    // Exemplo de transições de status
    public function iniciarTriagem($id)
    {
        return $this->updateStatus($id, 'triagem');
    }

    public function finalizarTriagem($id)
    {
        return $this->updateStatus($id, 'triagem_finalizada');
    }

    public function iniciarAtendimento($id)
    {
        return $this->updateStatus($id, 'em_atendimento');
    }

    public function finalizarAtendimento($id)
    {
        return $this->updateStatus($id, 'atendido');
    }

    public function cancelar($id)
    {
        return $this->updateStatus($id, 'cancelado');
    }

    public function noShow($id)
    {
        return $this->updateStatus($id, 'no_show');
    }

    private function updateStatus($id, $status)
    {
        $fichaModel = new FichaModel();
        $ficha = $fichaModel->find($id);

        if (!$ficha) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Ficha não encontrada']);
        }

        $fichaModel->update($id, ['status' => $status]);

        return $this->response->setJSON(['message' => "Ficha atualizada para $status"]);
    }
}
