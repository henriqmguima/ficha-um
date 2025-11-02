<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FichaModel;
use App\Models\MedicoModel;

class MedicoController extends BaseController
{
    public function index()
    {
        $usuario = session()->get('usuarioLogado');

        if (!$usuario || $usuario['role'] !== 'medico') {
            return redirect()->to('/login');
        }

        $fichaModel = new FichaModel();
        $medicoModel = new MedicoModel();

        // 🔹 Busca o registro do médico vinculado a este usuário
        $medico = $medicoModel
            ->where('usuario_id', $usuario['id'])
            ->first();

        if (!$medico) {
            return view('medico/erro', ['mensagem' => 'Perfil de médico não encontrado.']);
        }

        // 🔹 Busca o nome do médico (da tabela usuários)
        $nomeMedico = $usuario['nome'] ?? 'Médico';

        // Fichas atribuídas a este médico e prontas (acolhidas)
        $fichasDisponiveis = $fichaModel
            ->where('posto_id', $medico['posto_id'])
            ->where('status', 'acolhido')
            ->orderBy('prioridade_manchester', 'ASC')
            ->orderBy('criado_em', 'ASC')
            ->findAll();

        // Fichas em atendimento por este médico
        $fichasEmAtendimento = $fichaModel
            ->where('medico_id', $medico['id'])
            ->where('status', 'em_atendimento')
            ->orderBy('inicio_atendimento', 'ASC')
            ->findAll();

        return view('medico/index', [
            'medico' => $medico,
            'nomeMedico' => $nomeMedico,
            'fichasDisponiveis' => $fichasDisponiveis,
            'fichasEmAtendimento' => $fichasEmAtendimento,
        ]);
    }

    public function assumirFicha($id)
    {
        $usuario = session()->get('usuarioLogado');

        if (!$usuario || $usuario['role'] !== 'medico') {
            return redirect()->to('/login');
        }

        $fichaModel = new FichaModel();
        $medicoModel = new MedicoModel();

        $ficha = $fichaModel->find($id);
        $medico = $medicoModel->where('usuario_id', $usuario['id'])->first();

        if (!$ficha || !$medico) {
            return redirect()->back()->with('erro', 'Ficha ou médico não encontrados.');
        }

        // 🔹 Atualiza a ficha com o médico responsável
        $fichaModel->update($id, [
            'medico_id' => $medico['id'],
            'status' => 'em_atendimento',
            'inicio_atendimento' => date('Y-m-d H:i:s'),
        ]);

        // 🔹 Incrementa o contador diário do médico
        $medicoModel->update($medico['id'], [
            'atendimentos_hoje' => $medico['atendimentos_hoje'] + 1,
        ]);

        return redirect()->to('/medico');
    }
    public function apiFichas()
    {
        $usuario = session()->get('usuarioLogado');

        if (!$usuario || $usuario['role'] !== 'medico') {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Acesso negado']);
        }

        $medicoModel = new MedicoModel();
        $fichaModel = new FichaModel();

        $medico = $medicoModel->where('usuario_id', $usuario['id'])->first();
        if (!$medico) {
            return $this->response->setJSON(['fichas' => []]);
        }

        // apenas fichas atribuídas a este médico e prontas
        $fichas = $fichaModel
            ->where('medico_id', $medico['id'])
            ->where('status', 'acolhido')
            ->orderBy('criado_em', 'ASC')
            ->findAll();

        return $this->response->setJSON(['fichas' => $fichas]);
    }

    public function finalizarFicha($id)
    {
        $usuario = session()->get('usuarioLogado');

        if (!$usuario || $usuario['role'] !== 'medico') {
            return redirect()->to('/login');
        }

        $fichaModel = new FichaModel();
        $ficha = $fichaModel->find($id);

        if (!$ficha) {
            return redirect()->back()->with('erro', 'Ficha não encontrada.');
        }

        $fichaModel->update($id, [
            'status' => 'atendido',
            'fim_atendimento' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/medico')->with('success', 'Atendimento finalizado.');
    }

    public function verFicha($id)
    {
        $usuario = session()->get('usuarioLogado');

        if (!$usuario || $usuario['role'] !== 'medico') {
            return redirect()->to('/login');
        }

        $fichaModel = new FichaModel();
        $ficha = $fichaModel->find($id);

        if (!$ficha) {
            return view('medico/erro', ['mensagem' => 'Ficha não encontrada.']);
        }

        return view('medico/ver_ficha', ['ficha' => $ficha]);
    }
}
