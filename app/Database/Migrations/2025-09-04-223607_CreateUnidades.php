<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUnidades extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'auto_increment' => true],
            'nome'            => ['type' => 'VARCHAR', 'constraint' => 255],
            'cnes'            => ['type' => 'VARCHAR', 'constraint' => 20],
            'cep'             => ['type' => 'VARCHAR', 'constraint' => 10],
            'telefone'        => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'municipio'       => ['type' => 'VARCHAR', 'constraint' => 120],
            'uf'              => ['type' => 'CHAR', 'constraint' => 2],
            'logradouro'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'bairro'          => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'numero'          => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'fichas_disponiveis' => ['type' => 'INT', 'default' => 0],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('unidades');
    }

    public function down()
    {
        $this->forge->dropTable('unidades', true);
    }
}
