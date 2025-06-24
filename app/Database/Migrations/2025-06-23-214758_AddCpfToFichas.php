<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCpfToFichas extends Migration
{
    public function up()
    {
        $this->forge->addColumn('fichas', [
            'cpf' => [
                'type'       => 'VARCHAR',
                'constraint' => 14,
                'after'      => 'nome_paciente',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('fichas', 'cpf');
    }
}
