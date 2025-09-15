<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\TipoAtendimentoModel;

class TiposAtendimentoController extends BaseController
{
    public function index()
    {
        $tipos = (new TipoAtendimentoModel())->findAll();
        return $this->response->setJSON($tipos);
    }
}
