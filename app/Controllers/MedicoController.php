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

        $medico = $medicoModel->where('usuario_id', $usuario['id'])->first();

        if (!$medico) {
            return view('medico/erro', ['mensagem' => 'Perfil de médico não encontrado.']);
        }

        $nomeMedico = $usuario['nome'] ?? 'Médico';

        // Função auxiliar para calcular tempo de espera
        $calcularEspera = function ($criadoEm) {
            $agora = new \DateTime();
            $inicio = new \DateTime($criadoEm);
            $diff = $inicio->diff($agora);
            if ($diff->h > 0) {
                return $diff->h . 'h ' . $diff->i . 'min';
            }
            return $diff->i . ' min';
        };

        //  Fichas aguardando atendimento
        $fichasDisponiveis = $fichaModel
            ->where('posto_id', $medico['posto_id'])
            ->where('status', 'acolhido')
            ->orderBy('prioridade_manchester', 'ASC')
            ->orderBy('criado_em', 'ASC')
            ->findAll();

        foreach ($fichasDisponiveis as &$ficha) {
            $ficha['tempo_espera'] = $calcularEspera($ficha['criado_em']);
        }

        //  Fichas em atendimento
        $fichasEmAtendimento = $fichaModel
            ->where('medico_id', $medico['id'])
            ->where('status', 'em_atendimento')
            ->orderBy('inicio_atendimento', 'ASC')
            ->findAll();

        foreach ($fichasEmAtendimento as &$ficha) {
            $ficha['tempo_espera'] = $calcularEspera($ficha['criado_em']);
        }

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

        //  Atualiza a ficha com o médico responsável
        $fichaModel->update($id, [
            'medico_id' => $medico['id'],
            'status' => 'em_atendimento',
            'inicio_atendimento' => date('Y-m-d H:i:s'),
        ]);

        //  Incrementa o contador diário do médico
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
        $fichaModel  = new FichaModel();

        $medico = $medicoModel->where('usuario_id', $usuario['id'])->first();
        if (!$medico) {
            return $this->response->setJSON(['fichas' => []]);
        }

        $fichas = $fichaModel
            ->where('posto_id', $medico['posto_id'])
            ->where('status', 'acolhido')
            ->orderBy('prioridade_manchester', 'ASC')
            ->orderBy('criado_em', 'ASC')
            ->findAll();

        //  adiciona tempo_espera calculado
        $agora = new \DateTime();
        foreach ($fichas as &$f) {
            $inicio = new \DateTime($f['criado_em']);
            $diff   = $inicio->diff($agora);
            $f['tempo_espera'] = ($diff->h > 0)
                ? $diff->h . 'h ' . $diff->i . 'min'
                : $diff->i . ' min';
        }

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
