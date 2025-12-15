<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterStatusFieldInFichas extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('fichas', [
            'status' => [
                'name'       => 'status',
                'type'       => 'ENUM',
                'constraint' => ['aguardando', 'acolhido', 'chamado', 'em_atendimento', 'atendido'],
                'default'    => 'aguardando',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('fichas', [
            'status' => [
                'name'       => 'status',
                'type'       => 'ENUM',
                'constraint' => ['aguardando', 'em_atendimento', 'atendido'],
                'default'    => 'aguardando',
            ],
        ]);
    }
}
