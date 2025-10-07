<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FichaModel;

class Usuario extends BaseController
{
    public function index()
    {
        $usuario = session()->get('usuarioLogado');

        // 🔹 Redireciona se não estiver logado ou se não for paciente
        if (!$usuario || $usuario['role'] !== 'usuario') {
            return redirect()->to('/login');
        }

        $model = new FichaModel();

        // 🔹 Busca a ficha mais recente do usuário logado (pelo CPF)
        $ficha = $model->where('cpf', $usuario['cpf'])
            ->orderBy('criado_em', 'DESC')
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
                    ->orderBy('criado_em', 'ASC')
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

        // 🔹 Calcular posição na fila
        $todas = $model->where('status', 'aguardando')
            ->orderBy('criado_em', 'ASC')
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
