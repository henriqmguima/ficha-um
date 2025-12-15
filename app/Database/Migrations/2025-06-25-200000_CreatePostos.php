<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePostos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'nome'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'cnes'       => ['type' => 'VARCHAR', 'constraint' => 20],
            'endereco'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'cep'        => ['type' => 'VARCHAR', 'constraint' => 10],
            'cidade'     => ['type' => 'VARCHAR', 'constraint' => 100],
            'estado'     => ['type' => 'VARCHAR', 'constraint' => 100],
            'tipo'       => ['type' => 'ENUM', 'constraint' => ['publico', 'privado']],
            'criado_em' => ['type' => 'DATETIME', 'null' => false],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('postos');
    }

    public function down()
    {
        $this->forge->dropTable('postos');
    }
}
