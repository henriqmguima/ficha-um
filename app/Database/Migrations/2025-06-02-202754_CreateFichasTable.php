<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFichasTable extends Migration
{
public function up()
{
    $this->forge->addField([
        'id' => [
            'type' => 'INT',
            'constraint' => 11,
            'unsigned' => true,
            'auto_increment' => true,
        ],
        'nome_paciente' => [
            'type' => 'VARCHAR',
            'constraint' => '100',
        ],
        'tipo_atendimento' => [
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => true,
        ],
        'status' => [
            'type' => 'ENUM',
            'constraint' => ['aguardando', 'em_atendimento', 'atendido'],
            'default' => 'aguardando',
        ],
        'criado_em' => [
            'type' => 'DATETIME',
            'null' => false,
        ],
    ]);
    $this->forge->addKey('id', true);
    $this->forge->createTable('fichas');
}


    public function down()
    {
        $this->forge->dropTable('fichas');
    }

}
