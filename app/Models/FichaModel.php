<?php

namespace App\Models;

use CodeIgniter\Model;

class FichaModel extends Model
{
    protected $table      = 'fichas';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'usuario_id',
        'cpf',
        'nome_paciente',
        'tipo_atendimento',
        'status',
        'criado_em',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'criado_em';
    protected $updatedField  = ''; // sem updated por enquanto

    // Formato dos dados retornados
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
}
