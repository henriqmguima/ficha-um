<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\PostoModel;
use App\Models\UsuarioModel;
use App\Models\MedicoModel;
use App\Models\FichaModel;

class FichaSeeder extends Seeder
{
    public function run()
    {
        helper('date');

        $postoModel = new PostoModel();
        $usuarioModel = new UsuarioModel();
        $medicoModel = new MedicoModel();
        $fichaModel = new FichaModel();

        $postos = $postoModel->findAll();

        $tipos = [
            "Consulta Geral", "Dor abdominal", "Febre", "Tosse persistente",
            "Alergia", "Mal-estar", "Avaliação geral", "Cefaleia", "Tontura"
        ];

        $statusList = ['aguardando', 'acolhido', 'chamado', 'atendido'];

        foreach ($postos as $posto) {

            // Pacientes do posto
            $usuarios = $usuarioModel
                ->where('posto_id', $posto['id'])
                ->where('role', 'usuario')
                ->findAll();

            // Médicos do posto
            $medicos = $medicoModel
                ->where('posto_id', $posto['id'])
                ->findAll();

            if (empty($usuarios)) continue;

            $totalFichas = rand(10, 20);

            for ($i = 0; $i < $totalFichas; $i++) {

                $paciente = $usuarios[array_rand($usuarios)];
                $tipo = $tipos[array_rand($tipos)];
                $status = $statusList[array_rand($statusList)];

                // Data aleatória nas últimas 24 horas
                $timestamp = time() - rand(300, 86400);
                $criadoEm = date('Y-m-d H:i:s', $timestamp);

                $ficha = [
                    'usuario_id'       => $paciente['id'],
                    'nome_paciente'    => $paciente['nome'],
                    'cpf'              => $paciente['cpf'],
                    'tipo_atendimento' => $tipo,
                    'posto_id'         => $posto['id'],
                    'status'           => $status,
                    'autenticada'      => ($status == 'aguardando' ? 0 : 1),
                    'criado_em'        => $criadoEm,
                ];

                // status que envolvem médico
                if (in_array($status, ['acolhido', 'chamado', 'atendido']) && !empty($medicos)) {
                    $medico = $medicos[array_rand($medicos)];
                    $ficha['medico_id'] = $medico['id'];
                }

                // Início atendimento
                if (in_array($status, ['chamado', 'atendido'])) {
                    $ficha['inicio_atendimento'] =
                        date('Y-m-d H:i:s', $timestamp + rand(300, 1800));
                }

                // Finalizado
                if ($status == 'atendido') {
                    $ficha['fim_atendimento'] =
                        date('Y-m-d H:i:s', $timestamp + rand(1800, 3600));
                }

                $fichaModel->insert($ficha);
            }
        }

        echo "Seeder de fichas criado com sucesso!\n";
    }
}
