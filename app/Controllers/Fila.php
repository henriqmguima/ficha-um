<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\FichaModel;

class Fila extends BaseController
{
    public function index()
    {
        return view('fila/consulta');
    }

    public function resultado()
    {
        $id = $this->request->getPost('id');

        $model = new FichaModel();
        $ficha = $model->find($id);

        if (!$ficha) {
            return view('fila/consulta', ['erro' => 'Ficha não encontrada.']);
        }

        if ($ficha['status'] !== 'aguardando') {
            return view('fila/consulta', [
                'ficha' => $ficha,
                'mensagem' => 'Sua ficha já está em atendimento ou foi atendida.',
                'posicao' => null,
            ]);
        }

        // calcular posição
        $todas = $model->where('status', 'aguardando')->orderBy('criado_em', 'ASC')->findAll();
        $posicao = 1;
        foreach ($todas as $f) {
            if ($f['id'] == $ficha['id']) break;
            $posicao++;
        }

        return view('fila/consulta', [
            'ficha' => $ficha,
            'posicao' => $posicao,
        ]);
    }
}
