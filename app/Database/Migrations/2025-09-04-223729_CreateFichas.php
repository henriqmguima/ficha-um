<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFichas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                 => ['type' => 'INT', 'auto_increment' => true],
            'unidade_id'         => ['type' => 'INT'],
            'usuario_id'         => ['type' => 'INT', 'null' => true],
            'admin_id'           => ['type' => 'INT', 'null' => true], // último admin que alterou
            'cpf'                => ['type' => 'VARCHAR', 'constraint' => 14, 'null' => true],
            'nome_paciente'      => ['type' => 'VARCHAR', 'constraint' => 255],
            'tipo_atendimento_id' => ['type' => 'INT', 'null' => true],
            'origem'             => ['type' => 'ENUM', 'constraint' => ['remoto', 'presencial'], 'default' => 'remoto'],
            'status'             => ['type' => 'ENUM', 'constraint' => [
                'aguardando_autenticacao',
                'aguardando_triagem',
                'em_triagem',
                'aguardando_atendimento',
                'em_atendimento',
                'concluida',
                'cancelada',
                'no_show'
            ], 'default' => 'aguardando_autenticacao'],
            'created_at'         => ['type' => 'DATETIME', 'null' => true],
            'updated_at'         => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'         => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('unidade_id', 'unidades', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('admin_id', 'admins', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('tipo_atendimento_id', 'tipos_atendimento', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('fichas');
    }

    public function down()
    {
        $this->forge->dropTable('fichas', true);
    }
}
