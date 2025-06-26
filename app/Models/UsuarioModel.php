<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'cpf',
        'nome',
        'cartao_sus',
        'endereco',
        'email',
        'senha',
        'is_admin',
        'posto_id',   
        'criado_em'
    ];

    protected $useTimestamps = false;
}
