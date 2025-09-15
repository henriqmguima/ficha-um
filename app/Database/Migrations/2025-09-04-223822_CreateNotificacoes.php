<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificacoes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'ficha_id'   => ['type' => 'INT'],
            'canal'      => ['type' => 'ENUM', 'constraint' => ['whatsapp', 'sms', 'email'], 'default' => 'whatsapp'],
            'template'   => ['type' => 'VARCHAR', 'constraint' => 60, 'default' => 'fila_triagem_pos2'],
            'enviado_em' => ['type' => 'DATETIME', 'null' => true],
            'status'     => ['type' => 'ENUM', 'constraint' => ['pendente', 'enviado', 'erro'], 'default' => 'pendente'],
            'detalhes'   => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('ficha_id', 'fichas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('notificacoes');
    }

    public function down()
    {
        $this->forge->dropTable('notificacoes', true);
    }
}
