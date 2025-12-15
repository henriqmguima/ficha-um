<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMedicos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'usuario_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => false,
            ],
            'posto_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => false,
            ],
            'max_atendimentos' => [
                'type'       => 'INT',
                'default'    => 12, // máximo de atendimentos por médico
                'null'       => false,
            ],
            'atendimentos_hoje' => [
                'type'       => 'INT',
                'default'    => 0, // contador diário
                'null'       => false,
            ],
            'ativo' => [
                'type'       => 'BOOLEAN',
                'default'    => true,
            ],
            'criado_em' => [
                'type'       => 'DATETIME',
                'null'       => false,
            ],
        ]);

        $this->forge->addKey('id', true);



        $this->forge->createTable('medicos');
        // 🔹 Foreign keys
        $this->db->query('
            ALTER TABLE medicos 
            ADD CONSTRAINT fk_medico_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE ON UPDATE CASCADE,
            ADD CONSTRAINT fk_medico_posto FOREIGN KEY (posto_id) REFERENCES postos(id) ON DELETE CASCADE ON UPDATE CASCADE
        ');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE medicos DROP FOREIGN KEY fk_medico_usuario');
        $this->db->query('ALTER TABLE medicos DROP FOREIGN KEY fk_medico_posto');
        $this->forge->dropTable('medicos');
    }
}
