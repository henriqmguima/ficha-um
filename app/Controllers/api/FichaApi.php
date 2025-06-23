<?php
namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FichaModel;

class FichaApi extends ResourceController
{
    protected $format = 'json';

    // POST /api/fichas
    public function create()
    {
        $data = $this->request->getJSON(true);

        if (!isset($data['nome_paciente'])) {
            return $this->failValidationErrors('O campo nome_paciente é obrigatório.');
        }

        $model = new FichaModel();

        $fichaId = $model->insert([
            'nome_paciente'    => $data['nome_paciente'],
            'tipo_atendimento' => $data['tipo_atendimento'] ?? null,
            'status'           => 'aguardando',
            'criado_em'        => date('Y-m-d H:i:s'),
        ]);

        return $this->respondCreated(['id' => $fichaId]);
    }

    // GET /api/fichas/minha-ficha?id=3
    public function minhaFicha()
    {
        $id = $this->request->getGet('id');
        if (!$id) {
            return $this->failValidationErrors('ID da ficha é obrigatório.');
        }

        $model = new FichaModel();
        $minhaFicha = $model->find($id);

        if (!$minhaFicha) {
            return $this->failNotFound('Ficha não encontrada.');
        }

        if ($minhaFicha['status'] !== 'aguardando') {
            return $this->respond([
                'status' => $minhaFicha['status'],
                'mensagem' => 'Sua ficha já foi atendida ou está em atendimento.'
            ]);
        }

        // Buscar todas as fichas "aguardando", em ordem de chegada
        $todas = $model->where('status', 'aguardando')->orderBy('criado_em', 'ASC')->findAll();

        // Descobrir a posição da ficha
        $posicao = 1;
        foreach ($todas as $ficha) {
            if ($ficha['id'] == $minhaFicha['id']) {
                break;
            }
            $posicao++;
        }

        return $this->respond([
            'id' => $minhaFicha['id'],
            'nome_paciente' => $minhaFicha['nome_paciente'],
            'status' => $minhaFicha['status'],
            'posicao_na_fila' => $posicao
        ]);
    }
}
