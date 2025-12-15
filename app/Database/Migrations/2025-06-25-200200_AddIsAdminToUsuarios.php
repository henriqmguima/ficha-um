<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsAdminToUsuarios extends Migration
{
    public function up()
    {
        $this->forge->addColumn('usuarios', [
            'is_admin' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'after' => 'email',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('usuarios', 'is_admin');
    }
}
