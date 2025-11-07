<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FichaModel;
use App\Models\PostoModel;

class Usuario extends BaseController
{
    public function index()
    {
        $usuario = session()->get('usuarioLogado');

        // Redireciona se não estiver logado ou se não for paciente
        if (!$usuario || $usuario['role'] !== 'usuario') {
            return redirect()->to('/login');
        }

        // Buscar nome do posto
        $postoModel = new PostoModel();
        $posto = $postoModel->find($usuario['posto_id']);

        $nomePosto = $posto['nome'] ?? 'Posto não informado';

        $fichaModel = new FichaModel();

        // Busca a ficha mais recente do usuário logado (pelo CPF)
        $ficha = $fichaModel->where('cpf', $usuario['cpf'])
            ->orderBy('criado_em', 'DESC')
            ->first();

        $data = [
            'ficha'     => null,
            'posicao'   => null,
            'mensagem'  => '',
            'usuario'   => $usuario,
            'postoNome' => $nomePosto,
        ];

        if (!$ficha) {
            $data['mensagem'] = 'Você ainda não possui nenhuma ficha.';
        } else {
            $data['ficha'] = $ficha;

            // Se a ficha estiver aguardando, calcular posição na fila
            if ($ficha['status'] === 'aguardando') {
                $aguardando = $fichaModel->where('status', 'aguardando')
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

        // Calcular posição na fila
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
