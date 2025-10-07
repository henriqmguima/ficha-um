<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\FichaModel;
use App\Models\UsuarioModel;

class FichaController extends BaseController
{
    public function index()
    {
        $usuarioLogado = session()->get('usuarioLogado');

        // 🔹 Verifica se está logado e se é um papel autorizado
        if (
            !$usuarioLogado ||
            !in_array($usuarioLogado['role'], ['admin', 'diretor', 'medico'])
        ) {
            return redirect()->to('/users');
        }

        $postoId = $usuarioLogado['posto_id'] ?? null;
        $role = $usuarioLogado['role'];

        $fichaModel = new FichaModel();
        $usuarioModel = new UsuarioModel();

        // 🔹 Filtro de status (aguardando, em_atendimento, atendido)
        $statusFiltro = $this->request->getGet('status');
        $builder = $fichaModel->orderBy('criado_em', 'ASC');

        if ($statusFiltro && in_array($statusFiltro, ['aguardando', 'em_atendimento', 'atendido'])) {
            $builder->where('status', $statusFiltro);
        }

        // 🔹 Admin vê tudo; diretor e médico só do próprio posto
        if ($role !== 'admin' && $postoId) {
            $builder->where('posto_id', $postoId);
        }

        $fichas = $builder->findAll();

        // 🔹 Calcula posição e tempo de espera
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

        // 🔹 Carrega apenas usuários comuns do mesmo posto
        $usuariosQuery = $usuarioModel->where('role', 'usuario');
        if ($role !== 'admin' && $postoId) {
            $usuariosQuery->where('posto_id', $postoId);
        }
        $usuarios = $usuariosQuery->findAll();

        return view('admin/fichas/index', [
            'fichas'       => $fichas,
            'statusAtual'  => $statusFiltro ?? 'todos',
            'usuarios'     => $usuarios,
        ]);
    }

    public function create()
    {
        $usuarioLogado = session()->get('usuarioLogado');
        $postoId = $usuarioLogado['posto_id'] ?? null;
        $role = $usuarioLogado['role'] ?? null;

        if (!$usuarioLogado || !in_array($role, ['admin', 'diretor'])) {
            return redirect()->to('/users');
        }

        $usuarioModel = new UsuarioModel();

        // 🔹 Apenas usuários comuns do mesmo posto (ou todos se admin)
        $usuariosQuery = $usuarioModel->where('role', 'usuario');
        if ($role !== 'admin' && $postoId) {
            $usuariosQuery->where('posto_id', $postoId);
        }
        $usuarios = $usuariosQuery->findAll();

        return view('admin/fichas/create', ['usuarios' => $usuarios]);
    }

    public function store()
    {
        $usuarioLogado = session()->get('usuarioLogado');
        $postoId = $usuarioLogado['posto_id'] ?? null;
        $role = $usuarioLogado['role'] ?? null;

        if (!$usuarioLogado || !in_array($role, ['admin', 'diretor'])) {
            return redirect()->to('/users');
        }

        $usuarioModel = new UsuarioModel();
        $cpf = $this->request->getPost('cpf');

        // 🔹 Garante que só busca pacientes do mesmo posto (exceto admin)
        $pacienteQuery = $usuarioModel->where('cpf', $cpf)->where('role', 'usuario');
        if ($role !== 'admin' && $postoId) {
            $pacienteQuery->where('posto_id', $postoId);
        }
        $paciente = $pacienteQuery->first();

        if (!$paciente) {
            return redirect()->back()->with('error', 'Paciente não encontrado neste posto.');
        }

        $fichaModel = new FichaModel();

        $fichaModel->save([
            'usuario_id'        => $paciente['id'],
            'nome_paciente'     => $paciente['nome'],
            'cpf'               => $paciente['cpf'],
            'tipo_atendimento'  => $this->request->getPost('tipo_atendimento'),
            'status'            => 'aguardando',
            'posto_id'          => $postoId,
            'criado_em'         => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(site_url('admin/fichas'));
    }

    public function updateStatus($id = null, $novoStatus = null)
    {
        $usuarioLogado = session()->get('usuarioLogado');
        $role = $usuarioLogado['role'] ?? null;

        // 🔹 Apenas médico, diretor ou admin podem mudar status
        if (!$usuarioLogado || !in_array($role, ['admin', 'diretor', 'medico'])) {
            return redirect()->to('/users');
        }

        $fichaModel = new FichaModel();
        $ficha = $fichaModel->find($id);

        if ($ficha && in_array($novoStatus, ['aguardando', 'em_atendimento', 'atendido'])) {
            $dados = ['status' => $novoStatus];

            if ($novoStatus === 'em_atendimento') {
                $dados['inicio_atendimento'] = date('Y-m-d H:i:s');
            }

            if ($novoStatus === 'atendido') {
                $dados['fim_atendimento'] = date('Y-m-d H:i:s');
            }

            $fichaModel->update($id, $dados);
        }

        return redirect()->to(site_url('admin/fichas'));
    }

    public function delete($id = null)
    {
        $usuarioLogado = session()->get('usuarioLogado');
        $role = $usuarioLogado['role'] ?? null;

        // 🔹 Apenas diretor ou admin podem excluir fichas
        if (!$usuarioLogado || !in_array($role, ['admin', 'diretor'])) {
            return redirect()->to('/users');
        }

        $fichaModel = new FichaModel();
        $fichaModel->delete($id);

        return redirect()->to(site_url('admin/fichas'));
    }
}
