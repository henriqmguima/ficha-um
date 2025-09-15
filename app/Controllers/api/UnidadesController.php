<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UnidadeModel;
use App\Models\HorarioFuncionamentoModel;

class UnidadesController extends BaseController
{
    public function index()
    {
        $unidades = (new UnidadeModel())->findAll();
        return $this->response->setJSON($unidades);
    }

    public function show($id)
    {
        $unidade = (new UnidadeModel())->find($id);
        if (!$unidade) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Unidade não encontrada']);
        }
        return $this->response->setJSON($unidade);
    }

    public function horarios($id)
    {
        $horarios = (new HorarioFuncionamentoModel())->where('unidade_id', $id)->findAll();
        return $this->response->setJSON($horarios);
    }
}
