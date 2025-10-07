<?php

namespace App\Models;

use CodeIgniter\Model;

class PostoModel extends Model
{
    protected $table            = 'postos';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'cnes',
        'nome',
        'endereco',
        'cep',
        'cidade',
        'estado',
        'criado_em'
    ];
    protected $useTimestamps = false;
}
