<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class DadosIniciaisSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $now = Time::now('America/Sao_Paulo', 'pt_BR')->toDateTimeString();

        // === POSTOS ===
        $db->table('postos')->insertBatch([
            [
                'cnes' => '1234567',
                'nome' => 'UBS Central de Charqueadas',
                'endereco' => 'Rua Principal, 1000',
                'cep' => '96745-000',
                'cidade' => 'Charqueadas',
                'estado' => 'RS',
                'criado_em' => $now,
            ],
            [
                'cnes' => '7654321',
                'nome' => 'UBS Bairro Sul',
                'endereco' => 'Av. Secundária, 200',
                'cep' => '96745-001',
                'cidade' => 'Charqueadas',
                'estado' => 'RS',
                'criado_em' => $now,
            ]
        ]);

        // === USUÁRIOS ===
        $db->table('usuarios')->insertBatch([
            [
                'cpf' => '11111111111',
                'nome' => 'Carlos Diretor',
                'cartao_sus' => '123456789000',
                'endereco' => 'Rua A, 10',
                'email' => 'diretor@ubs.com',
                'senha' => password_hash('123456', PASSWORD_DEFAULT),
                'role' => 'diretor',
                'posto_id' => 1,
                'criado_em' => $now,
            ],
            [
                'cpf' => '22222222222',
                'nome' => 'Dr. João Médico',
                'cartao_sus' => '987654321000',
                'endereco' => 'Rua B, 20',
                'email' => 'medico@ubs.com',
                'senha' => password_hash('123456', PASSWORD_DEFAULT),
                'role' => 'medico',
                'posto_id' => 1,
                'criado_em' => $now,
            ],
            [
                'cpf' => '33333333333',
                'nome' => 'Maria Oliveira',
                'cartao_sus' => '555555555555',
                'endereco' => 'Rua C, 30',
                'email' => 'maria@paciente.com',
                'senha' => password_hash('123456', PASSWORD_DEFAULT),
                'role' => 'usuario',
                'posto_id' => 1,
                'criado_em' => $now,
            ],
            [
                'cpf' => '44444444444',
                'nome' => 'José Santos',
                'cartao_sus' => '666666666666',
                'endereco' => 'Rua D, 40',
                'email' => 'jose@paciente.com',
                'senha' => password_hash('123456', PASSWORD_DEFAULT),
                'role' => 'usuario',
                'posto_id' => 1,
                'criado_em' => $now,
            ],
        ]);

        // === MÉDICOS ===
        $db->table('medicos')->insert([
            'usuario_id' => 2, // Dr. João Médico
            'posto_id' => 1,
            'max_atendimentos' => 12,
            'atendimentos_hoje' => 3,
            'ativo' => true,
            'criado_em' => $now,
        ]);

        // === FICHAS ===
        $db->table('fichas')->insertBatch([
            [
                'usuario_id' => 3,
                'nome_paciente' => 'Maria Oliveira',
                'cpf' => '33333333333',
                'tipo_atendimento' => 'Consulta Clínica',
                'status' => 'aguardando',
                'posto_id' => 1,
                'medico_id' => null,
                'autenticada' => false,
                'sinais_vitais' => null,
                'sintomas' => 'Febre, dor de cabeça e tosse',
                'prioridade_manchester' => 'amarelo',
                'criado_em' => $now,
                'inicio_atendimento' => null,
                'fim_atendimento' => null,
            ],
            [
                'usuario_id' => 4,
                'nome_paciente' => 'José Santos',
                'cpf' => '44444444444',
                'tipo_atendimento' => 'Curativo',
                'status' => 'em_atendimento',
                'posto_id' => 1,
                'medico_id' => 1, // Dr. João
                'autenticada' => true,
                'sinais_vitais' => 'Pressão: 120/80, Temperatura: 36.8°C, FC: 78 bpm',
                'sintomas' => 'Corte superficial na mão direita',
                'prioridade_manchester' => 'verde',
                'criado_em' => $now,
                'inicio_atendimento' => $now,
                'fim_atendimento' => null,
            ],
        ]);
    }
}
