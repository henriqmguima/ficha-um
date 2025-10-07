<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsuarios extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'posto_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
                'after'    => 'id',
            ],
            'cpf' => [
                'type'       => 'VARCHAR',
                'constraint' => '14',
                'unique'     => true,
            ],
            'nome' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'cartao_sus' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'endereco' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'senha' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'role' => [ // 🔹 substitui is_admin e is_diretor
                'type'       => 'ENUM',
                'constraint' => ['admin', 'diretor', 'medico', 'usuario'],
                'default'    => 'usuario',
                'null'       => false,
                'after'      => 'senha',
            ],
            'criado_em' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id', true);

        // 🔹 Cria a tabela
        $this->forge->createTable('usuarios');

        // 🔹 Adiciona a foreign key para postos
        $this->db->query('
            ALTER TABLE usuarios 
            ADD CONSTRAINT fk_usuarios_posto 
            FOREIGN KEY (posto_id) 
            REFERENCES postos(id) 
            ON DELETE CASCADE 
            ON UPDATE CASCADE
        ');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE usuarios DROP FOREIGN KEY fk_usuarios_posto');
        $this->forge->dropTable('usuarios');
    }
}
