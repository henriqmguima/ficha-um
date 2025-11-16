<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class UsuariosSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $now = Time::now('America/Sao_Paulo', 'pt_BR')->toDateTimeString();
        $senhaPadrao = password_hash("123456", PASSWORD_DEFAULT);

        // ============================
        // LISTA REALISTA DE NOMES
        // ============================
        $nomes = [
            "Ana Paula Silva",
            "João Mendes",
            "Carlos Eduardo Barros",
            "Mariana Oliveira",
            "Fernanda Lima",
            "Ricardo Santos",
            "Patrícia Almeida",
            "Gustavo Nogueira",
            "Larissa Ferreira",
            "Bruno Castro",
            "Juliana Cardoso",
            "Daniel Moreira",
            "Camila Costa",
            "Thiago Martins",
            "Beatriz Rocha",
            "Felipe Carvalho",
            "Renata Souza",
            "Lucas Ribeiro",
            "Isabela Monteiro",
            "André Gomes",
            "Viviane Duarte",
            "Eduardo Farias",
            "Natália Pinto",
            "Rafael Moura",
            "Letícia Amaral",
            "Vinícius Peixoto",
            "Paulo Henrique Silveira",
            "Aline Chaves",
            "Diego Mota",
            "Sabrina Guedes",
            "Rodrigo Lacerda",
            "Marcela Freitas",
            "Henrique Vasconcelos",
            "Amanda Rezende",
            "Cláudia Machado",
            "Fábio Braga",
            "Sérgio Queiroz",
            "Tatiana Ramos",
            "Leonardo Prado",
            "Márcio Teles",
            "Bianca Salles",
            "Renan Azevedo",
            "Gabriela Campos",
            "Félix Antunes",
            "Joana Barreto",
            "Cristiano Pires",
            "Helena Morais",
            "Hugo Aragão",
        ];

        shuffle($nomes); // embaralha lista

        // ============================
        // CARREGA POSTOS
        // ============================
        $postos = $db->table('postos')->get()->getResultArray();

        if (empty($postos)) {
            echo "⚠️ Nenhum posto encontrado. Execute primeiro o PostosSeeder.\n";
            return;
        }

        $usuarios = [];
        $medicos = [];

        $iNome = 0; // ponteiro da lista de nomes
        $cpfBase = 50000000000;

        foreach ($postos as $posto) {

            // Slug para criar e-mails únicos
            $slug = strtolower(str_replace(' ', '_', $posto['nome']));

            // -----------------------------------------------------
            // 1️⃣ DIRETOR (1 por posto)
            // -----------------------------------------------------
            $cpfBase++;
            $nomeDiretor = $nomes[$iNome % count($nomes)];
            $iNome++;
            $emailDiretor = $slug . ".diretor@" . rand(1, 999) . ".ubs.com";

            $usuarios[] = [
                'cpf'        => strval($cpfBase),
                'nome'       => $nomeDiretor,
                'cartao_sus' => rand(100000000000, 999999999999),
                'endereco'   => "Rua do Diretor, nº " . rand(10, 500),
                'email'      => $emailDiretor,
                'senha'      => $senhaPadrao,
                'role'       => 'diretor',
                'posto_id'   => $posto['id'],
                'criado_em'  => $now,
            ];

            // -----------------------------------------------------
            // 2️⃣ MÉDICOS (2 por posto)
            // -----------------------------------------------------
            for ($m = 1; $m <= 2; $m++) {
                $cpfBase++;
                $nomeMedico = $nomes[$iNome % count($nomes)];
                $iNome++;
                $emailMedico = $slug . ".med" . $m . "." . rand(1, 999) . "@ubs.com";

                $usuarios[] = [
                    'cpf'        => strval($cpfBase),
                    'nome'       => $nomeMedico,
                    'cartao_sus' => rand(100000000000, 999999999999),
                    'endereco'   => "Rua do Médico {$m}, nº " . rand(10, 500),
                    'email'      => $emailMedico,
                    'senha'      => $senhaPadrao,
                    'role'       => 'medico',
                    'posto_id'   => $posto['id'],
                    'criado_em'  => $now,
                ];
            }

            // -----------------------------------------------------
            // 3️⃣ PACIENTES (5 por posto)
            // -----------------------------------------------------
            for ($p = 1; $p <= 5; $p++) {
                $cpfBase++;
                $nomePaciente = $nomes[$iNome % count($nomes)];
                $iNome++;
                $emailPaciente = $slug . ".pac" . $p . "." . rand(1, 999) . "@email.com";

                $usuarios[] = [
                    'cpf'        => strval($cpfBase),
                    'nome'       => $nomePaciente,
                    'cartao_sus' => rand(100000000000, 999999999999),
                    'endereco'   => "Rua do Paciente {$p}, nº " . rand(10, 800),
                    'email'      => $emailPaciente,
                    'senha'      => $senhaPadrao,
                    'role'       => 'usuario',
                    'posto_id'   => $posto['id'],
                    'criado_em'  => $now,
                ];
            }
        }

        // ======================================
        // Salva todos os usuários
        // ======================================
        $db->table('usuarios')->insertBatch($usuarios);

        // Recupera IDs reais
        $usuariosSalvos = $db->table('usuarios')->get()->getResultArray();

        // Criar médicos reais
        foreach ($usuariosSalvos as $user) {
            if ($user['role'] === 'medico') {
                $medicos[] = [
                    'usuario_id'        => $user['id'],
                    'posto_id'          => $user['posto_id'],
                    'max_atendimentos'  => 12,
                    'atendimentos_hoje' => rand(0, 5),
                    'ativo'             => true,
                    'criado_em'         => $now,
                ];
            }
        }

        $db->table('medicos')->insertBatch($medicos);

        echo "✔️ Seeder com nomes reais, diretores, médicos e pacientes criado com sucesso.\n";
    }
}
