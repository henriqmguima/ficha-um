<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHorariosAtendimentoToFichas extends Migration
{
    public function up()
    {
        $this->forge->addColumn('fichas', [
            'inicio_atendimento' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'criado_em',
            ],
            'fim_atendimento' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'inicio_atendimento',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('fichas', ['inicio_atendimento', 'fim_atendimento']);
    }
}
