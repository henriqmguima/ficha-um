<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPostoFieldsToUsuarios extends Migration
{
    public function up()
    {
        $this->forge->addColumn('usuarios', [
            'posto_id' => [
                'type'       => 'INT',
                'null'       => true,
                'after'      => 'id',
            ],
            'is_diretor' => [
                'type'       => 'BOOLEAN',
                'default'    => false,
                'after'      => 'is_admin',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('usuarios', ['posto_id', 'is_diretor']);
    }
}
