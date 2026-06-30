<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuariosModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $protectFields = true;
    protected $allowedFields = ['email', 'senha_hash', 'tipo', 'reset_token', 'reset_token_date'];

    protected $validationRules = [
        'email' => 'required|valid_email|is_unique[usuarios.email,id,{id}]',
    ];

    protected $skipValidation = true;

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }

    public function buscar(string $termo = ''): array
    {
        $builder = $this->orderBy('email', 'ASC');

        if ($termo !== '') {
            $builder->like('email', $termo);
        }

        $usuarios = $builder->findAll();

        foreach ($usuarios as &$usuario) {
            // Compatibilidade com as telas do painel de usuários.
            $usuario['nome'] = $usuario['nome'] ?? ($usuario['email'] ?? '');
            $usuario['role'] = $usuario['role'] ?? ((($usuario['tipo'] ?? 'user') === 'admin') ? 'super_admin' : 'usuario');
            $usuario['status'] = $usuario['status'] ?? 1;
            $usuario['telefone'] = $usuario['telefone'] ?? '';
            $usuario['senha'] = $usuario['senha'] ?? ($usuario['senha_hash'] ?? '');
        }
        unset($usuario);

        return $usuarios;
    }
}
