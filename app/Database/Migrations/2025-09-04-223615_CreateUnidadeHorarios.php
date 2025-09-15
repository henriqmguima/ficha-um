<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUnidadeHorarios extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'unidade_id' => ['type' => 'INT'],
            'dia_semana' => ['type' => 'TINYINT', 'comment' => '0=domingo ... 6=sabado'],
            'abre'       => ['type' => 'TIME', 'null' => true],
            'fecha'      => ['type' => 'TIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('unidade_id', 'unidades', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('unidade_horarios');
    }

    public function down()
    {
        $this->forge->dropTable('unidade_horarios', true);
    }
}
