<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\FichaModel;
use App\Models\UsuarioModel;

class FichaController extends BaseController
{
    public function index()
    {
        $usuario = session()->get('usuarioLogado');

        // Somente Admins ou Diretores podem acessar
        if (!$usuario || (!isset($usuario['id_admin']) && !isset($usuario['id_diretor']))) {
            return redirect()->to('/users');
        }

        $model = new FichaModel();

        $statusFiltro = $this->request->getGet('status');
        $builder = $model->orderBy('created_at', 'ASC');

        if ($statusFiltro && in_array($statusFiltro, ['triagem', 'aguardando', 'chamado'])) {
            $builder->where('status', $statusFiltro);
        }

        // Se tiver unidade associada, filtra por ela
        if (isset($usuario['unidade_id'])) {
            $builder->where('unidade_id', $usuario['unidade_id']);
        }

        $fichas = $builder->findAll();

        // Calcular posição e tempo de espera
        $posicao = 1;
        foreach ($fichas as &$ficha) {
            if ($ficha['status'] === 'aguardando') {
                $ficha['posicao'] = $posicao++;

                $criado = new \DateTime($ficha['created_at'], new \DateTimeZone('America/Sao_Paulo'));
                $agora  = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
                $intervalo = $criado->diff($agora);
                $ficha['tempo_espera'] = $intervalo->format('%H:%I:%S');
            } else {
                $ficha['posicao'] = '—';
                $ficha['tempo_espera'] = '—';
            }

            $ficha['data_formatada'] = date('d/m/Y H:i', strtotime($ficha['created_at']));
        }

        $usuarioModel = new UsuarioModel();
        $usuarios = $usuarioModel->where('unidade_id', $usuario['unidade_id'])->findAll();

        return view('admin/fichas/index', [
            'fichas'      => $fichas,
            'statusAtual' => $statusFiltro ?? 'todos',
            'usuarios'    => $usuarios, // para o modal de criar ficha
        ]);
    }

    public function create()
    {
        $usuario = session()->get('usuarioLogado');
        $usuarioModel = new UsuarioModel();

        $usuarios = $usuarioModel->where('unidade_id', $usuario['unidade_id'])->findAll();

        return view('admin/fichas/create', ['usuarios' => $usuarios]);
    }

    public function store()
    {
        $usuario = session()->get('usuarioLogado');
        $usuarioModel = new UsuarioModel();

        $cpf = $this->request->getPost('cpf');
        $paciente = $usuarioModel->where('cpf', $cpf)
            ->where('unidade_id', $usuario['unidade_id'])
            ->first();

        if (!$paciente) {
            return redirect()->back()->with('error', 'Paciente não encontrado.');
        }

        $model = new FichaModel();
        $model->insert([
            'usuario_id'       => $paciente['id'],
            'nome_paciente'    => $paciente['nome'],
            'cpf'              => $paciente['cpf'],
            'tipo_atendimento' => $this->request->getPost('tipo_atendimento'),
            'status'           => 'aguardando',
            'unidade_id'       => $usuario['unidade_id'],
            'created_at'        => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(site_url('admin/fichas'));
    }

    public function updateStatus($id = null, $novoStatus = null)
    {
        $model = new FichaModel();
        $ficha = $model->find($id);

        if ($ficha && in_array($novoStatus, ['triagem', 'aguardando', 'chamado'])) {
            $dados = ['status' => $novoStatus];

            if ($novoStatus === 'aguardando') {
                $dados['inicio_atendimento'] = date('Y-m-d H:i:s');
            }

            if ($novoStatus === 'chamado') {
                $dados['fim_atendimento'] = date('Y-m-d H:i:s');
            }

            $model->update($id, $dados);
        }

        return redirect()->to(site_url('admin/fichas'));
    }

    public function delete($id = null)
    {
        $model = new FichaModel();
        $model->delete($id);

        return redirect()->to(site_url('admin/fichas'));
    }
}
