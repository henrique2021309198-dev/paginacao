<?php

namespace App\Models;

use CodeIgniter\Model;

class TotemModel extends Model
{
    protected $table = 'totens';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields = true;
    protected $allowedFields = ['nome', 'codigo', 'ativo'];
    protected $skipValidation = true;

    public function listarAtivos(): array
    {
        return $this->where('ativo', 1)
            ->orderBy('nome', 'ASC')
            ->findAll();
    }

    public function buscarPorCodigo(string $codigo): ?array
    {
        return $this->where('codigo', $codigo)->first();
    }
}