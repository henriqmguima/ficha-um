<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\FichaModel;
use App\Models\UsuarioModel;
use App\Models\MedicoModel;

class FichaController extends BaseController
{
    public function index()
    {
        $usuarioLogado = session()->get('usuarioLogado');
        if (!$usuarioLogado || !in_array($usuarioLogado['role'], ['admin', 'diretor'])) {
            return redirect()->to('/users');
        }

        $postoId = $usuarioLogado['posto_id'];
        $fichaModel = new FichaModel();
        $usuarioModel = new UsuarioModel();

        // Buscar fichas do posto (aguardando + acolhido por exemplo)
        $fichas = $fichaModel
            ->where('posto_id', $postoId)
            ->where('status', 'aguardando') // apenas as que precisam de triagem
            ->orderBy('criado_em', 'ASC')
            ->findAll();

        // calcula posicao/tempo (mantém sua lógica)
        $posicao = 1;
        foreach ($fichas as &$ficha) {
            if ($ficha['status'] === 'aguardando') {
                $ficha['posicao'] = $posicao++;
                $criado = new \DateTime($ficha['criado_em']);
                $agora = new \DateTime('now');
                $intervalo = $criado->diff($agora);
                $ficha['tempo_espera'] = $intervalo->format('%H:%I:%S');
            } else {
                $ficha['posicao'] = '—';
                $ficha['tempo_espera'] = '—';
            }
            $ficha['data_formatada'] = date('d/m/Y H:i', strtotime($ficha['criado_em']));
        }

        // Carrega usuários (pacientes) do posto para o modal de criação de ficha
        $usuariosQuery = $usuarioModel->where('role', 'usuario');
        if ($postoId) {
            $usuariosQuery->where('posto_id', $postoId);
        }
        $usuarios = $usuariosQuery->findAll();

        return view('admin/fichas/index', [
            'fichas'      => $fichas,
            'statusAtual' => $this->request->getGet('status') ?? 'todos',
            'usuarios'    => $usuarios,
        ]);
    }
    public function store()
    {
        $usuarioLogado = session()->get('usuarioLogado');

        //  Permite apenas admin ou diretor criarem fichas
        if (!$usuarioLogado || !in_array($usuarioLogado['role'], ['admin', 'diretor'])) {
            return redirect()->to('/users');
        }

        $postoId = $usuarioLogado['posto_id'] ?? null;
        $usuarioModel = new UsuarioModel();
        $fichaModel = new FichaModel();

        $cpf = $this->request->getPost('cpf');
        $tipoAtendimento = $this->request->getPost('tipo_atendimento');

        //  Busca o paciente pelo CPF
        $paciente = $usuarioModel
            ->where('cpf', $cpf)
            ->where('role', 'usuario')
            ->first();

        if (!$paciente) {
            return redirect()->back()->with('error', 'Paciente não encontrado.');
        }

        //  Cria nova ficha
        $fichaData = [
            'usuario_id'        => $paciente['id'],
            'nome_paciente'     => $paciente['nome'],
            'cpf'               => $paciente['cpf'],
            'tipo_atendimento'  => $tipoAtendimento,
            'status'            => 'aguardando',
            'posto_id'          => $postoId,
            'autenticada'       => 0,
            'criado_em'         => date('Y-m-d H:i:s'),
        ];

        $fichaModel->insert($fichaData);

        return redirect()->to(site_url('admin/fichas'))
            ->with('success', 'Ficha criada com sucesso e adicionada à fila de triagem!');
    }

    //  Função de triagem (avaliação)
    public function avaliar($id)
    {
        $fichaModel = new FichaModel();
        $ficha = $fichaModel->find($id);

        if (!$ficha) {
            return redirect()->to('admin/fichas')->with('error', 'Ficha não encontrada.');
        }

        return view('admin/fichas/avaliar', ['ficha' => $ficha]);
    }

    public function salvarAvaliacao($id)
    {
        $fichaModel = new FichaModel();
        $medicoModel = new MedicoModel();

        $ficha = $fichaModel->find($id);
        if (!$ficha) {
            return redirect()->to(site_url('admin/fichas'))->with('error', 'Ficha não encontrada.');
        }

        // monta sinais vitais
        $sinais = [
            'temperatura' => $this->request->getPost('temperatura'),
            'pressao'     => $this->request->getPost('pressao'),
            'frequencia'  => $this->request->getPost('frequencia'),
        ];

        // dados a salvar
        $dados = [
            'sinais_vitais'           => json_encode($sinais),
            'sintomas'               => $this->request->getPost('sintomas'),
            'prioridade_manchester'  => $this->request->getPost('prioridade_manchester'),
            'autenticada'            => 1,
            'status'                 => 'acolhido', // triado e pronto para enviar ao médico
        ];

        // find médico com menos atendimentos no mesmo posto
        $postoId = $ficha['posto_id'] ?? null;
        $medico = null;
        if ($postoId) {
            $medico = $medicoModel
                ->where('posto_id', $postoId)
                ->where('ativo', 1)
                ->orderBy('atendimentos_hoje', 'ASC')
                ->first();
        }

        if ($medico) {
            $dados['medico_id'] = $medico['id'];

            // incrementa contador do médico (atomicidade simples)
            $medicoModel->update($medico['id'], [
                'atendimentos_hoje' => (int)$medico['atendimentos_hoje'] + 1,
            ]);
        }

        $fichaModel->update($id, $dados);

        // redireciona com flash (não JSON)
        return redirect()->to(site_url('admin/fichas'))->with('success', 'Ficha avaliada e enviada ao médico.');
    }
    //  Atualiza status (genérico)
    public function updateStatus($id, $novoStatus)
    {
        $fichaModel = new FichaModel();

        if (!in_array($novoStatus, ['aguardando', 'acolhido', 'chamado', 'atendido'])) {
            return redirect()->back()->with('error', 'Status inválido.');
        }

        $dados = ['status' => $novoStatus];
        if ($novoStatus === 'chamado') {
            $dados['inicio_atendimento'] = date('Y-m-d H:i:s');
        } elseif ($novoStatus === 'atendido') {
            $dados['fim_atendimento'] = date('Y-m-d H:i:s');
        }

        $fichaModel->update($id, $dados);
        return redirect()->back()->with('success', 'Status atualizado.');
    }
    // dentro de Admin\FichaController
    public function apiListar()
    {
        $usuarioLogado = session()->get('usuarioLogado');
        if (!$usuarioLogado || !in_array($usuarioLogado['role'], ['admin', 'diretor'])) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Acesso negado']);
        }

        $postoId = $usuarioLogado['posto_id'] ?? null;
        $fichaModel = new FichaModel();

        $fichas = $fichaModel
            ->where('posto_id', $postoId)
            ->orderBy('criado_em', 'ASC')
            ->findAll();

        // calcula posicao/tempo similar ao index
        $posicao = 1;
        foreach ($fichas as &$ficha) {
            if ($ficha['status'] === 'aguardando') {
                $ficha['posicao'] = $posicao++;
                $criado = new \DateTime($ficha['criado_em']);
                $agora = new \DateTime('now');
                $intervalo = $criado->diff($agora);
                $ficha['tempo_espera'] = $intervalo->format('%H:%I:%S');
                $ficha['inicio_timestamp'] = $criado->getTimestamp();
            } else {
                $ficha['posicao'] = null;
                $ficha['tempo_espera'] = null;
                $ficha['inicio_timestamp'] = null;
            }
            $ficha['data_formatada'] = date('d/m/Y H:i', strtotime($ficha['criado_em']));
        }
        unset($ficha);

        return $this->response->setJSON($fichas);
    }
    public function delete($id)
    {
        $usuarioLogado = session()->get('usuarioLogado');

        // 🔐 Apenas administradores ou diretores podem excluir fichas
        if (!$usuarioLogado || !in_array($usuarioLogado['role'], ['admin', 'diretor'])) {
            return redirect()->to('/login')->with('error', 'Acesso negado.');
        }

        $fichaModel = new FichaModel();
        $ficha = $fichaModel->find($id);

        if (!$ficha) {
            return redirect()->back()->with('error', 'Ficha não encontrada.');
        }

        //  Exclui a ficha
        $fichaModel->delete($id);

        return redirect()
            ->to(site_url('admin/fichas'))
            ->with('success', 'Ficha excluída com sucesso.');
    }
}
