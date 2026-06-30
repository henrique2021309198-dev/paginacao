<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TabelaPedidosProdutos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_pedido' => ['type' => 'INT', 'constraint' => 11,],
            'id_produto' => ['type' => 'INT', 'constraint' => 11,],
            'quantidade' => ['type' => 'INT', 'constraint' => 11,],
            'preco_unitario' => ['type' => 'float'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('pedidos_produtos');
    }

    public function down()
    {
        $this->forge->dropTable('pedidos_produtos');
    }
}
