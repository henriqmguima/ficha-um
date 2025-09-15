<?php

namespace App\Models;

use CodeIgniter\Model;

class HorarioFuncionamentoModel extends Model
{
    protected $table            = 'unidade_horarios';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'unidade_id',
        'dia_semana',
        'abre',
        'fecha',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $returnType    = 'array';

    // Regras de validação
    protected $validationRules = [
        'unidade_id' => 'required|is_natural_no_zero',
        'dia_semana' => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[6]',
        'abre'       => 'permit_empty|valid_time',
        'fecha'      => 'permit_empty|valid_time',
    ];

    protected $validationMessages = [
        'dia_semana' => [
            'less_than_equal_to' => 'O dia da semana deve estar entre 0 (domingo) e 6 (sábado).'
        ],
    ];
}
