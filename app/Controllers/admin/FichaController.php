<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\FichaModel;

class FichaController extends BaseController
{
    public function index()
    {

        $usuario = session()->get('usuarioLogado');

        if (!is_array($usuario) || !isset($usuario['is_admin']) || !$usuario['is_admin']) {
            return redirect()->to('/fila'); // redireciona usuário comum para a tela pública
        }

        $model = new FichaModel();

        $statusFiltro = $this->request->getGet('status');
        $builder = $model->orderBy('criado_em', 'ASC');

        if ($statusFiltro && in_array($statusFiltro, ['aguardando', 'em_atendimento', 'atendido'])) {
            $builder->where('status', $statusFiltro);
        }

        $fichas = $builder->findAll();

        $posicaoFila = 1;
        foreach ($fichas as &$ficha) {
            if ($ficha['status'] === 'aguardando') {
                $ficha['posicao'] = $posicaoFila++;
            } else {
                $ficha['posicao'] = '—';
            }
        }

        $data['fichas'] = $fichas;
        $data['statusAtual'] = $statusFiltro ?? 'todos';

        return view('admin/fichas/index', $data);
    }
    public function create()
    {
        return view('admin/fichas/create');
    }
    public function store()
    {
        $model = new FichaModel();

        $model->save([
            'nome_paciente'    => $this->request->getPost('nome_paciente'),
            'tipo_atendimento' => $this->request->getPost('tipo_atendimento'),
            'status'           => 'aguardando',
            'criado_em'        => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(site_url('admin/fichas'));
    }
    public function updateStatus($id = null, $novoStatus = null)
    {
        $model = new FichaModel();
        $ficha = $model->find($id);

        if ($ficha && in_array($novoStatus, ['aguardando', 'em_atendimento', 'atendido'])) {
            $dados = ['status' => $novoStatus];

            if ($novoStatus === 'em_atendimento') {
                $dados['inicio_atendimento'] = date('Y-m-d H:i:s');
            }

            if ($novoStatus === 'atendido') {
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

