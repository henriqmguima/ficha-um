<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class PostosSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now('America/Sao_Paulo', 'pt_BR')->toDateTimeString();

        $postos = [
            [
                'cnes'      => '1000001',
                'nome'      => 'UNIDADE DE SAÚDE DA FAMÍLIA CRUZ DE MALTA',
                'endereco'  => 'Rua Cruz de Malta, 101',
                'cep'       => '96745-000',
                'cidade'    => 'Charqueadas',
                'estado'    => 'RS',
                'criado_em' => $now,
            ],
            [
                'cnes'      => '1000002',
                'nome'      => 'UNIDADE DE SAÚDE DA FAMÍLIA CRUZ DE MALTA II',
                'endereco'  => 'Rua Cruz de Malta, 200',
                'cep'       => '96745-000',
                'cidade'    => 'Charqueadas',
                'estado'    => 'RS',
                'criado_em' => $now,
            ],
            [
                'cnes'      => '1000003',
                'nome'      => 'UNIDADE DE SAÚDE DA FAMÍLIA OSMAR WIENKE',
                'endereco'  => 'Av. Osmar Wienke, 55',
                'cep'       => '96745-000',
                'cidade'    => 'Charqueadas',
                'estado'    => 'RS',
                'criado_em' => $now,
            ],
            [
                'cnes'      => '1000004',
                'nome'      => 'UNIDADE DE SAÚDE DA FAMÍLIA PIRATINI',
                'endereco'  => 'Rua Piratini, 500',
                'cep'       => '96745-000',
                'cidade'    => 'Charqueadas',
                'estado'    => 'RS',
                'criado_em' => $now,
            ],
            [
                'cnes'      => '1000005',
                'nome'      => 'UNIDADE DE SAÚDE DA FAMÍLIA PIRATINI II',
                'endereco'  => 'Rua Piratini, 550',
                'cep'       => '96745-000',
                'cidade'    => 'Charqueadas',
                'estado'    => 'RS',
                'criado_em' => $now,
            ],
            [
                'cnes'      => '1000006',
                'nome'      => 'UNIDADE DE SAÚDE DA FAMÍLIA SANTO ANTÔNIO',
                'endereco'  => 'Rua Santo Antônio, 80',
                'cep'       => '96745-000',
                'cidade'    => 'Charqueadas',
                'estado'    => 'RS',
                'criado_em' => $now,
            ],
            [
                'cnes'      => '1000007',
                'nome'      => 'UNIDADE DE SAÚDE DA FAMÍLIA SÃO FRANCISCO',
                'endereco'  => 'Rua São Francisco, 210',
                'cep'       => '96745-000',
                'cidade'    => 'Charqueadas',
                'estado'    => 'RS',
                'criado_em' => $now,
            ],
            [
                'cnes'      => '1000008',
                'nome'      => 'UNIDADE DE SAÚDE DA FAMÍLIA SÃO MIGUEL',
                'endereco'  => 'Av. São Miguel, 300',
                'cep'       => '96745-000',
                'cidade'    => 'Charqueadas',
                'estado'    => 'RS',
                'criado_em' => $now,
            ],
            [
                'cnes'      => '1000009',
                'nome'      => 'UNIDADE DE SAÚDE DA FAMÍLIA SUL AMÉRICA',
                'endereco'  => 'Rua Sul América, 400',
                'cep'       => '96745-000',
                'cidade'    => 'Charqueadas',
                'estado'    => 'RS',
                'criado_em' => $now,
            ],
            [
                'cnes'      => '1000010',
                'nome'      => 'UNIDADE DE SAÚDE DA FAMÍLIA VICENTE PINTO',
                'endereco'  => 'Rua Vicente Pinto, 120',
                'cep'       => '96745-000',
                'cidade'    => 'Charqueadas',
                'estado'    => 'RS',
                'criado_em' => $now,
            ],
        ];

        $this->db->table('postos')->insertBatch($postos);
    }
}
