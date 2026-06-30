<?php

namespace App\Models;

use CodeIgniter\Model;

class PedidoModel extends Model
{
    protected $table         = 'pedidos';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['cliente_id', 'total', 'status'];

    protected $useTimestamps = true;

    protected $validationRules = [
        'cliente_id' => 'required|integer',
        'total'      => 'required|decimal|greater_than[0]',
        'status'     => 'required|in_list[pendente,processando,concluido,cancelado]',
    ];

    protected $validationMessages = [
        'cliente_id' => [
            'required' => 'O ID do cliente é obrigatório.',
            'integer'  => 'O ID do cliente deve ser um número inteiro.',
        ],
        'total' => [
            'required'      => 'O total do pedido é obrigatório.',
            'decimal'       => 'Informe um total válido (ex: 99.99).',
            'greater_than'  => 'O total deve ser maior que zero.',
        ],
        'status' => [
            'required'  => 'O status do pedido é obrigatório.',
            'in_list'   => 'O status deve ser um dos seguintes: pendente, processando, concluído, cancelado.',
        ],
    ];
}