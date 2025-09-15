<?php

namespace App\Models;

use CodeIgniter\Model;

class TipoAtendimentoModel extends Model
{
    protected $table            = 'tipos_atendimento';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'codigo',
        'nome',
        'prioridade',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $returnType    = 'array';

    // Regras de validação
    protected $validationRules = [
        'codigo'    => 'required|min_length[2]|max_length[30]|is_unique[tipos_atendimento.codigo,id,{id}]',
        'nome'      => 'required|min_length[3]|max_length[120]',
        'prioridade' => 'is_natural',
    ];

    protected $validationMessages = [
        'codigo' => [
            'is_unique' => 'Já existe um tipo de atendimento com este código.'
        ],
    ];
}
