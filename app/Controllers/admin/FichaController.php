<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\FichaModel;

class FichaController extends BaseController
{
    public function index()
    {
        $model = new FichaModel();
        $data['fichas'] = $model->orderBy('criado_em', 'ASC')->findAll();
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
            $model->update($id, ['status' => $novoStatus]);
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
