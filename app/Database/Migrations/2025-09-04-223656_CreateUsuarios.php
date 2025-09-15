<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsuarios extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                     => ['type' => 'INT', 'auto_increment' => true],
            'unidade_id'             => ['type' => 'INT'],
            'cpf'                    => ['type' => 'VARCHAR', 'constraint' => 14],
            'nome'                   => ['type' => 'VARCHAR', 'constraint' => 255],
            'cartao_sus'             => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'endereco'               => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'email'                  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'telefone'               => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'senha_hash'             => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'autenticacao_status'    => ['type' => 'ENUM', 'constraint' => ['pendente', 'aprovado', 'rejeitado'], 'default' => 'pendente'],
            'autenticado_em'         => ['type' => 'DATETIME', 'null' => true],
            'aprovado_por_admin_id'  => ['type' => 'INT', 'null' => true],
            'created_at'             => ['type' => 'DATETIME', 'null' => true],
            'updated_at'             => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'             => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['unidade_id', 'cpf']); // mesmo CPF em unidades diferentes pode existir
        $this->forge->addForeignKey('unidade_id', 'unidades', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('aprovado_por_admin_id', 'admins', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('usuarios');
    }

    public function down()
    {
        $this->forge->dropTable('usuarios', true);
    }
}
