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
        $postoModel   = new PostoModel();
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

        // Sintomas
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

        // Manchester
        $manchester = ["vermelho", "laranja", "amarelo", "verde", "azul"];

        // Sinais vitais
        $sinaisVitais = function () {
            return json_encode([
                "temperatura" => rand(36, 40),
                "pressao"     => rand(80, 130),
                "frequencia"  => rand(60, 120)
            ]);
        };

        $statusList = ['aguardando', 'acolhido', 'chamado', 'atendido'];

        foreach ($postos as $posto) {

            $usuarios = $usuarioModel
                ->where('posto_id', $posto['id'])
                ->where('role', 'usuario')
                ->findAll();

            $medicos = $medicoModel
                ->where('posto_id', $posto['id'])
                ->findAll();

            if (empty($usuarios)) {
                continue;
            }

            // 🔹 DATA BASE ÚNICA (HOJE às 07:00)
            $horaBase = strtotime(date('Y-m-d') . ' 18:00:00');

            // Quantidade de fichas
            $totalFichas = rand(8, 15);

            for ($i = 0; $i < $totalFichas; $i++) {

                $paciente = $usuarios[$i % count($usuarios)];
                $tipo     = $tipos[array_rand($tipos)];
                $status   = $statusList[array_rand($statusList)];

                // ⏱ Horário incremental (não repete)
                $criadoTimestamp = $horaBase + ($i * rand(5, 10) * 60);
                $criadoEm = date('Y-m-d H:i:s', $criadoTimestamp);

                $ficha = [
                    'usuario_id'       => $paciente['id'],
                    'nome_paciente'    => $paciente['nome'],
                    'cpf'              => $paciente['cpf'],
                    'tipo_atendimento' => $tipo,
                    'posto_id'         => $posto['id'],
                    'status'           => $status,
                    'autenticada'      => ($status === 'aguardando') ? 0 : 1,
                    'criado_em'        => $criadoEm,
                    'sintomas'         => $sintomasList[array_rand($sintomasList)],
                ];

                // Triagem
                if (in_array($status, ['acolhido', 'chamado', 'atendido'])) {
                    $ficha['prioridade_manchester'] = $manchester[array_rand($manchester)];
                    $ficha['sinais_vitais'] = $sinaisVitais();
                }

                // Médico
                if (in_array($status, ['acolhido', 'chamado', 'atendido']) && !empty($medicos)) {
                    $medico = $medicos[array_rand($medicos)];
                    $ficha['medico_id'] = $medico['id'];
                }

                // Início atendimento
                if (in_array($status, ['chamado', 'atendido'])) {
                    $ficha['inicio_atendimento'] =
                        date('Y-m-d H:i:s', $criadoTimestamp + rand(5, 15) * 60);
                }

                // Fim atendimento
                if ($status === 'atendido') {
                    $ficha['fim_atendimento'] =
                        date('Y-m-d H:i:s', $criadoTimestamp + rand(20, 40) * 60);
                }

                $fichaModel->insert($ficha);
            }
        }

        echo "✔️ Seeder de fichas atualizada (mesmo dia, horários únicos).\n";
    }
}
