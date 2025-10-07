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
        'nome_paciente',
        'cpf',
        'tipo_atendimento',
        'status',
        'posto_id',
        'criado_em',
        'inicio_atendimento',
        'fim_atendimento',
    ];


    protected $useTimestamps = true;
    protected $createdField  = 'criado_em';
    protected $updatedField  = ''; // sem updated por enquanto

    // Formato dos dados retornados
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
}
