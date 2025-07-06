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
            return $this->failValidationErrors('Campo nome_paciente é obrigatório.');
        }

        $usuario = session()->get('usuarioLogado');

        if (!$usuario) {
            return $this->failUnauthorized('Usuário não autenticado.');
        }

        $model = new FichaModel();

        // Verificar se o usuário já possui uma ficha "aguardando" ou "em_atendimento"
        $fichaExistente = $model
            ->where('usuario_id', $usuario['id'])
            ->whereIn('status', ['aguardando', 'em_atendimento'])
            ->first();

        if ($fichaExistente) {
            return $this->failResourceExists('Você já possui uma ficha ativa.');
        }

        $fichaId = $model->insert([
            'usuario_id'       => $usuario['id'],
            'cpf'              => $data['cpf'] ?? null,
            'nome_paciente'    => $data['nome_paciente'],
            'tipo_atendimento' => $data['tipo_atendimento'] ?? null,
            'status'           => 'aguardando',
            'criado_em'        => date('Y-m-d H:i:s'),
        ]);

        return $this->respondCreated(['id' => $fichaId]);
    }

    public function minhaFicha()
    {
        $usuario = session()->get('usuarioLogado');
        $cpf     = $this->request->getGet('cpf');
        $id      = $this->request->getGet('id');

        $model = new FichaModel();

        if ($usuario) {
            $minhaFicha = $model
                ->where('usuario_id', $usuario['id'])
                ->orderBy('criado_em', 'DESC')
                ->first();
        } elseif ($cpf) {
            $minhaFicha = $model
                ->where('cpf', $cpf)
                ->orderBy('criado_em', 'DESC')
                ->first();
        } elseif ($id) {
            $minhaFicha = $model->find($id);
        } else {
            return $this->failValidationErrors('ID do usuário, CPF ou ID da ficha é obrigatório.');
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

        $todas = $model
            ->where('status', 'aguardando')
            ->orderBy('criado_em', 'ASC')
            ->findAll();

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

    public function listar()
    {
        $model = new \App\Models\FichaModel();

        $statusFiltro = $this->request->getGet('status');
        $query = $model->orderBy('criado_em', 'ASC');

        if ($statusFiltro && in_array($statusFiltro, ['aguardando', 'em_atendimento', 'atendido'])) {
            $query->where('status', $statusFiltro);
        }

        $fichas = $query->findAll();

        $posicao = 1;
        foreach ($fichas as &$ficha) {
            if ($ficha['status'] === 'aguardando') {
                $ficha['posicao'] = $posicao++;
                $criado = new \DateTime($ficha['criado_em'], new \DateTimeZone('America/Sao_Paulo'));
                $agora = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
                $intervalo = $criado->diff($agora);
                $ficha['tempo_espera'] = $intervalo->format('%H:%I:%S');
            } else {
                $ficha['posicao'] = '—';
                $ficha['tempo_espera'] = '—';
            }

            $ficha['data_formatada'] = date('d/m/Y H:i', strtotime($ficha['criado_em']));
        }

        return $this->respond($fichas);
    }
}


