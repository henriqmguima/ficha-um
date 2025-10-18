<?php

namespace App\Models;

use CodeIgniter\Model;

class MedicoModel extends Model
{
    protected $table      = 'medicos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'usuario_id',
        'posto_id',
        'max_atendimentos',
        'atendimentos_hoje',
        'ativo',
        'criado_em'
    ];

    protected $useTimestamps = false;
    protected $returnType    = 'array';
}
