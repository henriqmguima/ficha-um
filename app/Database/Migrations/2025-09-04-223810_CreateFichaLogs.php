<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFichaLogs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'ficha_id'   => ['type' => 'INT'],
            'admin_id'   => ['type' => 'INT', 'null' => true],
            'de_status'  => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
            'para_status' => ['type' => 'VARCHAR', 'constraint' => 40],
            'motivo'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('ficha_id', 'fichas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('admin_id', 'admins', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('ficha_logs');
    }

    public function down()
    {
        $this->forge->dropTable('ficha_logs', true);
    }
}
