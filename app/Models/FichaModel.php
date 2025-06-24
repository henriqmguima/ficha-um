<?php

namespace App\Models;

use CodeIgniter\Model;

class FichaModel extends Model
{
    protected $table      = 'fichas';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'nome_paciente',
        'cpf',
        'tipo_atendimento',
        'status',
        'criado_em',
    ];

    // Se quiser que o CodeIgniter gerencie os timestamps:
    protected $useTimestamps = true;
    protected $createdField  = 'criado_em';
    protected $updatedField  = ''; // sem updated por enquanto

    // Formato dos dados retornados
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
}
