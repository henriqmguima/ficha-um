<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FichaModel;
use App\Models\UsuarioModel;

class FichaApi extends ResourceController
{
    protected $format = 'json';

    /**
     * Cria uma nova ficha (solicitação do paciente)
     */
    public function create()
    {
        $data = $this->request->getJSON(true);
        $usuario = session()->get('usuarioLogado');

        if (!$usuario) {
            return $this->failUnauthorized('Usuário não autenticado.');
        }

        if (!isset($data['nome_paciente'])) {
            return $this->failValidationErrors('Campo nome_paciente é obrigatório.');
        }

        $model = new FichaModel();
        $usuarioModel = new UsuarioModel();

        // Verifica se o usuário já possui ficha ativa
        $fichaExistente = $model
            ->where('usuario_id', $usuario['id'])
            ->whereIn('status', ['aguardando', 'em_atendimento', 'acolhido'])
            ->first();

        if ($fichaExistente) {
            return $this->failResourceExists('Você já possui uma ficha ativa.');
        }

        // 🔹 Captura o posto_id do usuário logado (importante!)
        // 🔹 Captura o posto_id do usuário logado
        $postoId = $usuario['posto_id'] ?? null;

        // 🔸 Se ainda estiver nulo, tenta puxar o posto padrão do banco
        if (!$postoId) {
            $usuarioModel = new UsuarioModel();
            $usuarioCompleto = $usuarioModel->find($usuario['id']);
            $postoId = $usuarioCompleto['posto_id'] ?? 1; // usa 1 como fallback (ou o ID padrão do posto principal)
        }

        // Cria a ficha com os mesmos campos da FichaController
        $fichaData = [
            'usuario_id'        => $usuario['id'],
            'nome_paciente'     => $usuario['nome'],
            'cpf'               => $usuario['cpf'],
            'tipo_atendimento'  => $data['tipo_atendimento'] ?? 'Clínico Geral',
            'status'            => 'aguardando',
            'posto_id'          => $postoId,
            'autenticada'       => 0,
            'criado_em'         => date('Y-m-d H:i:s'),
        ];

        $id = $model->insert($fichaData);

        return $this->respondCreated([
            'message' => 'Ficha criada com sucesso e adicionada à fila de triagem.',
            'id' => $id,
        ]);
    }

    /**
     * Retorna a ficha mais recente do paciente logado
     */
    public function minhaFicha()
    {
        $usuario = session()->get('usuarioLogado');
        $cpf     = $this->request->getGet('cpf');
        $id      = $this->request->getGet('id');

        $model = new FichaModel();
        $postoModel = new \App\Models\PostoModel();

        // 🔹 Localiza ficha (sessão, CPF ou ID)
        if ($usuario) {
            $ficha = $model
                ->where('usuario_id', $usuario['id'])
                ->orderBy('criado_em', 'DESC')
                ->first();
        } elseif ($cpf) {
            $ficha = $model
                ->where('cpf', $cpf)
                ->orderBy('criado_em', 'DESC')
                ->first();
        } elseif ($id) {
            $ficha = $model->find($id);
        } else {
            return $this->failValidationErrors('É necessário informar CPF ou ID.');
        }

        // 🔹 Nenhuma ficha encontrada
        if (!$ficha) {
            return $this->failNotFound('Nenhuma ficha ativa.');
        }

        // 🔹 Se a última ficha já foi atendida, trata como se não tivesse ficha ativa
        if ($ficha['status'] === 'atendido') {
            return $this->failNotFound('Nenhuma ficha ativa no momento. Você pode solicitar uma nova.');
        }

        // 🔹 Calcula tempo de espera
        $criado = new \DateTime($ficha['criado_em'], new \DateTimeZone('America/Sao_Paulo'));
        $agora = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        $intervalo = $criado->diff($agora);
        $tempo_espera = $intervalo->format('%H:%I:%S');

        // 🔹 Calcula posição na fila apenas se ainda estiver aguardando
        $posicao = null;
        if ($ficha['status'] === 'aguardando') {
            $todas = $model
                ->where('posto_id', $ficha['posto_id'])
                ->where('status', 'aguardando')
                ->orderBy('criado_em', 'ASC')
                ->findAll();

            $posicao = 1;
            foreach ($todas as $f) {
                if ($f['id'] == $ficha['id']) break;
                $posicao++;
            }
        }

        // 🔹 Busca nome do posto
        $posto = $postoModel->find($ficha['posto_id']);
        $postoNome = $posto['nome'] ?? 'Posto não identificado';

        // 🔹 Retorno padronizado
        return $this->respond([
            'id'                => $ficha['id'],
            'cpf'               => $ficha['cpf'],
            'nome_paciente'     => $ficha['nome_paciente'] ?? '—',
            'tipo_atendimento'  => $ficha['tipo_atendimento'] ?? '—',
            'status'            => $ficha['status'] ?? '—',
            'posto_id'          => $ficha['posto_id'] ?? null,
            'posto_nome'        => $postoNome,
            'autenticada'       => $ficha['autenticada'] ?? 0,
            'tempo_espera'      => $tempo_espera,
            'posicao_na_fila'   => $posicao,
            'mensagem'          => $ficha['status'] === 'aguardando'
                ? 'Ficha ativa e aguardando atendimento.'
                : 'Atendimento em andamento.',
        ]);
    }

    /**
     * Lista todas as fichas (usado para debug ou painel)
     */
    public function listar()
    {


        $model = new FichaModel();
        $fichas = $model->orderBy('criado_em', 'ASC')->findAll();

        $posicao = 1;
        foreach ($fichas as &$ficha) {
            if ($ficha['status'] === 'aguardando') {
                $ficha['posicao'] = $posicao++;
                $criado = new \DateTime($ficha['criado_em'], new \DateTimeZone('America/Sao_Paulo'));
                $agora = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
                $ficha['tempo_espera'] = $criado->diff($agora)->format('%H:%I:%S');
            } else {
                $ficha['posicao'] = '—';
                $ficha['tempo_espera'] = '—';
            }
        }
        unset($ficha);

        return $this->respond($fichas);
    }
}
