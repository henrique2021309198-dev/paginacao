<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTipoTabelaEstoque extends Migration
{
    public function up()
    {
        $this->forge->addColumn('estoques', [
            'tipo' => [
                'type' => 'varchar',
                'constraint' => 7,
                'null' => true,
                'comment' => 'entrada/saida']
            ]
        );
    }

    public function down()
    {
        $this->forget->dropColumn('estoques', 'tipo');
    }
}
