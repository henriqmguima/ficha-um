<?php
namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FichaModel;

class FichaApi extends ResourceController
{
    protected $format = 'json';

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
    public function minhaFicha()
    {
        $cpf = $this->request->getGet('cpf');
        $id  = $this->request->getGet('id');

        $model = new \App\Models\FichaModel();

        if ($cpf) {
            $minhaFicha = $model->where('cpf', $cpf)->orderBy('criado_em', 'DESC')->first();
        } elseif ($id) {
            $minhaFicha = $model->find($id);
        } else {
            return $this->failValidationErrors('CPF ou ID da ficha é obrigatório.');
        }

        if (!$minhaFicha) {
            return $this->failNotFound('Ficha não encontrada.');
        }

        if ($minhaFicha['status'] !== 'aguardando') {
            return $this->respond([
                'status' => $minhaFicha['status'],
                'nome_paciente' => $minhaFicha['nome_paciente'],
                'mensagem' => 'Sua ficha já foi atendida ou está em atendimento.'
            ]);
        }

        $todas = $model->where('status', 'aguardando')->orderBy('criado_em', 'ASC')->findAll();

        $posicao = 1;
        foreach ($todas as $ficha) {
            if ($ficha['id'] == $minhaFicha['id']) {
                break;
            }
            $posicao++;
        }

        return $this->respond([
            'id' => $minhaFicha['id'],
            'cpf' => $minhaFicha['cpf'],
            'nome_paciente' => $minhaFicha['nome_paciente'],
            'status' => $minhaFicha['status'],
            'posicao_na_fila' => $posicao
        ]);
    }
}
