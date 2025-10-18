<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTriagemFieldsToFichas extends Migration
{
    public function up()
    {
        $this->forge->addColumn('fichas', [
            'autenticada' => [
                'type'    => 'BOOLEAN',
                'default' => false,
                'after'   => 'status',
            ],
            'sinais_vitais' => [
                'type'    => 'TEXT',
                'null'    => true,
                'after'   => 'autenticada',
            ],
            'sintomas' => [
                'type'    => 'TEXT',
                'null'    => true,
                'after'   => 'sinais_vitais',
            ],
            'prioridade_manchester' => [
                'type'       => 'ENUM',
                'constraint' => ['vermelho', 'laranja', 'amarelo', 'verde', 'azul'],
                'null'       => true,
                'after'      => 'sintomas',
            ],
            'medico_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
                'after'    => 'posto_id',
            ],
        ]);

        // 🔹 Foreign key para médico
        $this->db->query('
            ALTER TABLE fichas 
            ADD CONSTRAINT fk_ficha_medico FOREIGN KEY (medico_id) REFERENCES medicos(id) ON DELETE SET NULL ON UPDATE CASCADE
        ');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE fichas DROP FOREIGN KEY fk_ficha_medico');
        $this->forge->dropColumn('fichas', [
            'autenticada',
            'sinais_vitais',
            'sintomas',
            'prioridade_manchester',
            'medico_id'
        ]);
    }
}
