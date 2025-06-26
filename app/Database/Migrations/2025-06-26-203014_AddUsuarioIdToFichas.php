<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUsuarioIdToFichas extends Migration
{
    public function up()
    {
        $this->forge->addColumn('fichas', [
            'usuario_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id'
            ]
        ]);

        // Criar relação (opcional, se quiser usar FK real)
        $this->db->query('ALTER TABLE fichas ADD CONSTRAINT fk_usuario_id FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL');
    }

    public function down()
    {
        $this->forge->dropForeignKey('fichas', 'fk_usuario_id');
        $this->forge->dropColumn('fichas', 'usuario_id');
    }
}
