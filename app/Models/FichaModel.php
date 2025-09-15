<?php

namespace App\Models;

use CodeIgniter\Model;

class FichaModel extends Model
{
    protected $table = 'fichas';
    protected $primaryKey = 'id_ficha';
    protected $returnType = 'array';

    protected $allowedFields = [
        'usuario_id',
        'unidade_id',
        'admin_id',
        'tipo_atendimento_id',
        'status', // aguardando, triagem, em_atendimento, atendido
        'created_at',
        'inicio_atendimento',
        'fim_atendimento'
    ];

    protected $useTimestamps = true;

    protected $validationRules = [
        'usuario_id' => 'required|is_natural_no_zero',
        'unidade_id' => 'required|is_natural_no_zero',
        'status'     => 'required|in_list[aguardando,triagem,em_atendimento,atendido]',
    ];
}
