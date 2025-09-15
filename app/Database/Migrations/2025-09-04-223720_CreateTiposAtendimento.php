<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTiposAtendimento extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'        => ['type' => 'INT', 'auto_increment' => true],
            'codigo'    => ['type' => 'VARCHAR', 'constraint' => 30],
            'nome'      => ['type' => 'VARCHAR', 'constraint' => 120],
            'prioridade' => ['type' => 'TINYINT', 'default' => 0], // 0=normal, maior = mais prioritário
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('codigo');
        $this->forge->createTable('tipos_atendimento');
    }

    public function down()
    {
        $this->forge->dropTable('tipos_atendimento', true);
    }
}
