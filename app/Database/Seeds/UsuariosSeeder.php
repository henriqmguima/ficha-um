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

        // Lista de nomes realistas
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
            "Hugo Aragão"
        ];
        shuffle($nomes);

        // Carrega postos
        $postos = $db->table('postos')->get()->getResultArray();

        if (empty($postos)) {
            echo "⚠️ Nenhum posto encontrado. Execute primeiro o PostosSeeder.\n";
            return;
        }

        // CPFs baseados na função
        $cpfDiretor = 10000000000;
        $cpfAdmin   = 20000000000;
        $cpfMedico  = 30000000000;
        $cpfUsuario = 40000000000;

        $usuarios = [];
        $medicosInsert = [];
        $iNome = 0;

        foreach ($postos as $posto) {

            $slug = strtolower(str_replace(' ', '_', $posto['nome']));

            // 1️⃣ Diretor (1 por posto)
            $cpfDiretor++;
            $usuarios[] = [
                'cpf'        => strval($cpfDiretor),
                'nome'       => $nomes[$iNome++ % count($nomes)],
                'cartao_sus' => rand(100000000000, 999999999999),
                'endereco'   => "Rua do Diretor, nº " . rand(10, 500),
                'email'      => "{$slug}.diretor@ubs.com",
                'senha'      => $senhaPadrao,
                'role'       => 'diretor',
                'posto_id'   => $posto['id'],
                'criado_em'  => $now,
            ];

            // 2️⃣ Admin (1 por posto)
            $cpfAdmin++;
            $usuarios[] = [
                'cpf'        => strval($cpfAdmin),
                'nome'       => $nomes[$iNome++ % count($nomes)],
                'cartao_sus' => rand(100000000000, 999999999999),
                'endereco'   => "Rua do Admin, nº " . rand(10, 500),
                'email'      => "{$slug}.admin@ubs.com",
                'senha'      => $senhaPadrao,
                'role'       => 'admin',
                'posto_id'   => $posto['id'],
                'criado_em'  => $now,
            ];

            // 3️⃣ Médicos (2)
            for ($m = 1; $m <= 2; $m++) {
                $cpfMedico++;
                $usuarios[] = [
                    'cpf'        => strval($cpfMedico),
                    'nome'       => $nomes[$iNome++ % count($nomes)],
                    'cartao_sus' => rand(100000000000, 999999999999),
                    'endereco'   => "Rua do Médico {$m}, nº " . rand(10, 500),
                    'email'      => "{$slug}.med{$m}@ubs.com",
                    'senha'      => $senhaPadrao,
                    'role'       => 'medico',
                    'posto_id'   => $posto['id'],
                    'criado_em'  => $now,
                ];
            }

            // 4️⃣ Pacientes (5)
            for ($p = 1; $p <= 5; $p++) {
                $cpfUsuario++;
                $usuarios[] = [
                    'cpf'        => strval($cpfUsuario),
                    'nome'       => $nomes[$iNome++ % count($nomes)],
                    'cartao_sus' => rand(100000000000, 999999999999),
                    'endereco'   => "Rua do Paciente {$p}, nº " . rand(10, 800),
                    'email'      => "{$slug}.pac{$p}@email.com",
                    'senha'      => $senhaPadrao,
                    'role'       => 'usuario',
                    'posto_id'   => $posto['id'],
                    'criado_em'  => $now,
                ];
            }
        }

        // Salva usuários
        $db->table('usuarios')->insertBatch($usuarios);

        // Inserir médicos na tabela 'medicos'
        $usuariosSalvos = $db->table('usuarios')->get()->getResultArray();
        foreach ($usuariosSalvos as $user) {
            if ($user['role'] === 'medico') {
                $medicosInsert[] = [
                    'usuario_id'        => $user['id'],
                    'posto_id'          => $user['posto_id'],
                    'max_atendimentos'  => 12,
                    'atendimentos_hoje' => rand(0, 5),
                    'ativo'             => true,
                    'criado_em'         => $now,
                ];
            }
        }

        if (!empty($medicosInsert)) {
            $db->table('medicos')->insertBatch($medicosInsert);
        }

        echo "✔️ Seeder padronizado com CPFs por função criado com sucesso.\n";
    }
}
