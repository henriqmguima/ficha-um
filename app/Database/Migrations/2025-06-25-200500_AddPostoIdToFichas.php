<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPostoIdToFichas extends Migration
{
    public function up()
    {
        // Adiciona a coluna posto_id na tabela fichas
        $this->forge->addColumn('fichas', [
            'posto_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false,
                'after' => 'id', // você pode mudar o local se quiser
            ],
        ]);

        // Cria a chave estrangeira (ajuste o nome da tabela se for diferente)
        $this->db->query('
            ALTER TABLE fichas 
            ADD CONSTRAINT fk_fichas_posto 
            FOREIGN KEY (posto_id) 
            REFERENCES postos(id) 
            ON DELETE CASCADE 
            ON UPDATE CASCADE
        ');
    }

    public function down()
    {
        // Remove a foreign key e a coluna se o rollback for executado
        $this->db->query('ALTER TABLE fichas DROP FOREIGN KEY fk_fichas_posto');
        $this->forge->dropColumn('fichas', 'posto_id');
    }
}
