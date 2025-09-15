<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDiretores extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'unidade_id' => ['type' => 'INT'],
            'cpf'        => ['type' => 'VARCHAR', 'constraint' => 14],
            'nome'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'email'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'telefone'   => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'senha_hash' => ['type' => 'VARCHAR', 'constraint' => 255],
            'is_active'  => ['type' => 'TINYINT', 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('cpf');
        $this->forge->addForeignKey('unidade_id', 'unidades', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('diretores');
    }

    public function down()
    {
        $this->forge->dropTable('diretores', true);
    }
}
