<?php

namespace App\Models;

use CodeIgniter\Model;

class PedidoProdutoModel extends Model
{
    protected $table         = 'pedidos_produtos';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['id_pedido', 'id_produto', 'quantidade', 'preco_unitario'];

    protected $useTimestamps = true;

    protected $validationRules = [
        'id_pedido' => 'required|integer',
        'id_produto' => 'required|integer',
        'quantidade' => 'required|integer|greater_than[0]',
        'preco_unitario' => 'required|decimal|greater_than[0]',
    ];

    protected $validationMessages = [
        'id_pedido' => [
            'required' => 'O ID do pedido é obrigatório.',
            'integer'  => 'O ID do pedido deve ser um número inteiro.',
        ],
        'id_produto' => [
            'required' => 'O ID do produto é obrigatório.',
            'integer'  => 'O ID do produto deve ser um número inteiro.',
        ],
        'quantidade' => [
            'required'     => 'A quantidade é obrigatória.',
            'integer'      => 'A quantidade deve ser um número inteiro.',
            'greater_than' => 'A quantidade deve ser maior que zero.',
        ],
        'preco_unitario' => [
            'required'      => 'O preço unitário é obrigatório.',
            'decimal'       => 'Informe um preço unitário válido (ex: 12.50).',
            'greater_than'  => 'O preço unitário deve ser maior que zero.',
        ],
    ];
}