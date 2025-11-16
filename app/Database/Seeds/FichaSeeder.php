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

        $postoModel  = new PostoModel();
        $usuarioModel = new UsuarioModel();
        $medicoModel  = new MedicoModel();
        $fichaModel   = new FichaModel();

        $postos = $postoModel->findAll();

        // Tipos de atendimento
        $tipos = [
            "Consulta Geral",
            "Dor abdominal",
            "Febre",
            "Tosse persistente",
            "Alergia",
            "Mal-estar",
            "Avaliação geral",
            "Cefaleia",
            "Tontura"
        ];

        // Possíveis sintomas
        $sintomasList = [
            "Dor forte na região abdominal",
            "Febre alta há mais de 2 dias",
            "Tosse contínua",
            "Tontura e mal-estar",
            "Dor de cabeça intensa",
            "Desconforto respiratório",
            "Náuseas e enjoo",
            "Cansaço excessivo",
            "Manchas na pele"
        ];

        // Triagem Manchester
        $manchester = ["vermelho", "laranja", "amarelo", "verde", "azul"];

        // Template de sinais vitais
        $sinaisVitaisTemplate = function () {
            return json_encode([
                "temperatura" => rand(36, 40),
                "pressao"     => rand(80, 130),
                "frequencia"  => rand(60, 120)
            ]);
        };

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

            // Número de fichas por posto
            $totalFichas = rand(8, 15);

            for ($i = 0; $i < $totalFichas; $i++) {

                $paciente = $usuarios[array_rand($usuarios)];
                $tipo = $tipos[array_rand($tipos)];
                $status = $statusList[array_rand($statusList)];

                // Criado nas últimas 24h
                $timestamp = time() - rand(300, 86400);
                $criadoEm = date('Y-m-d H:i:s', $timestamp);

                // Base da ficha
                $ficha = [
                    'usuario_id'       => $paciente['id'],
                    'nome_paciente'    => $paciente['nome'],
                    'cpf'              => $paciente['cpf'],
                    'tipo_atendimento' => $tipo,
                    'posto_id'         => $posto['id'],
                    'status'           => $status,
                    'autenticada'      => ($status === 'aguardando') ? 0 : 1,
                    'criado_em'        => $criadoEm,
                ];

                // Sempre preencher sintomas (simula texto do paciente)
                $ficha['sintomas'] = $sintomasList[array_rand($sintomasList)];

                // Triagem Manchester e sinais vitais somente se passou pela triagem
                if (in_array($status, ['acolhido', 'chamado', 'atendido'])) {
                    $ficha['prioridade_manchester'] = $manchester[array_rand($manchester)];
                    $ficha['sinais_vitais'] = $sinaisVitaisTemplate();
                }

                // status que envolvem médico
                if (in_array($status, ['acolhido', 'chamado', 'atendido']) && !empty($medicos)) {
                    $medico = $medicos[array_rand($medicos)];
                    $ficha['medico_id'] = $medico['id'];
                }

                // Início atendimento
                if (in_array($status, ['chamado', 'atendido'])) {
                    $ficha['inicio_atendimento'] = date('Y-m-d H:i:s', $timestamp + rand(120, 1800));
                }

                // Finalizado
                if ($status == 'atendido') {
                    $ficha['fim_atendimento'] = date('Y-m-d H:i:s', $timestamp + rand(1800, 3600));
                }

                $fichaModel->insert($ficha);
            }
        }

        echo "✔️ Seeder de fichas COMPLETA criada com sucesso!\n";
    }
}
