<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run()
    {
        $postoModel = new \App\Models\PostoModel();
        $usuarioModel = new \App\Models\UsuarioModel();

        // Criar unidade
        $postoId = $postoModel->insert([
            'cnes'      => '9013113',
            'nome'      => 'Unidade Piratini I',
            'endereco'  => 'Núcleo C-78, 5 - Vila Aços Finos Piratini',
            'cep'       => '96745000',
            'cidade'    => 'Charqueadas',
            'estado'    => 'Rio Grande do Sul',
            'criado_em' => date('Y-m-d H:i:s')
        ]);

        helper('text');

        // Criar dois administradores
        $admins = [
            [
                'cpf'       => '00011122233',
                'nome'      => 'Admin 1',
                'email'     => 'admin1@teste.com',
                'senha'     => password_hash('admin123', PASSWORD_DEFAULT),
                'role'  => "admin",
                'posto_id'  => $postoId,
                'criado_em' => date('Y-m-d H:i:s')
            ],
            [
                'cpf'       => '00011122244',
                'nome'      => 'Admin 2',
                'email'     => 'admin2@teste.com',
                'senha'     => password_hash('admin123', PASSWORD_DEFAULT),
                'role'  => "admin",
                'posto_id'  => $postoId,
                'criado_em' => date('Y-m-d H:i:s')
            ]
        ];

        $usuarioModel->insertBatch($admins);

        // Criar usuários comuns
        $usuarios = [];
        for ($i = 1; $i <= 5; $i++) {
            $usuarios[] = [
                'cpf'        => '1112223330' . $i,
                'nome'       => "Usuário $i",
                'cartao_sus' => '898000' . rand(100000, 999999),
                'endereco'   => "Rua Exemplo $i",
                'email'      => "usuario$i@teste.com",
                'senha'      => password_hash('usuario123', PASSWORD_DEFAULT),
                'is_admin'   => 0,
                'posto_id'   => $postoId,
                'criado_em'  => date('Y-m-d H:i:s')
            ];
        }

        $usuarioModel->insertBatch($usuarios);
    }
}
