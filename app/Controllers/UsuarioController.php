<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FichaModel;

class UsuarioController extends BaseController
{
    public function index()
    {
        $usuario = session()->get('usuarioLogado');

        if (!$usuario || !isset($usuario['id_usuario'])) {
            return redirect()->to('/login');
        }

        $model = new FichaModel();

        // Buscar ficha mais recente do usuário logado
        $ficha = $model->where('usuario_id', $usuario['id_usuario'])
            ->orderBy('created_at', 'DESC')
            ->first();

        $data = [
            'ficha'    => null,
            'posicao'  => null,
            'mensagem' => '',
        ];

        if (!$ficha) {
            $data['mensagem'] = 'Você ainda não possui nenhuma ficha.';
        } else {
            $data['ficha'] = $ficha;

            if ($ficha['status'] === 'aguardando') {
                $aguardando = $model->where('status', 'aguardando')
                    ->where('unidade_id', $usuario['unidade_id'])
                    ->orderBy('created_at', 'ASC')
                    ->findAll();

                foreach ($aguardando as $index => $f) {
                    if ($f['id'] == $ficha['id']) {
                        $data['posicao'] = $index + 1;
                        break;
                    }
                }
            }
        }

        return view('users/consulta', $data);
    }

    public function resultado()
    {
        $usuario = session()->get('usuarioLogado');

        if (!$usuario || !isset($usuario['id_usuario'])) {
            return redirect()->to('/login');
        }

        $id = $this->request->getPost('id');
        $model = new FichaModel();
        $ficha = $model->find($id);

        if (!$ficha) {
            return view('users/consulta', ['erro' => 'Ficha não encontrada.']);
        }

        if ($ficha['status'] !== 'aguardando') {
            return view('users/consulta', [
                'ficha'    => $ficha,
                'mensagem' => 'Sua ficha já está em atendimento ou foi atendida.',
                'posicao'  => null,
            ]);
        }

        // calcular posição dentro da unidade
        $todas = $model->where('status', 'aguardando')
            ->where('unidade_id', $ficha['unidade_id'])
            ->orderBy('created_at', 'ASC')
            ->findAll();

        $posicao = 1;
        foreach ($todas as $f) {
            if ($f['id'] == $ficha['id']) break;
            $posicao++;
        }

        return view('users/consulta', [
            'ficha'   => $ficha,
            'posicao' => $posicao,
        ]);
    }
}
