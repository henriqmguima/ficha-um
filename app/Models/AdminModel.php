<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'admins';
    protected $primaryKey = 'id_admin';
    protected $returnType = 'array';

    protected $allowedFields = [
        'nome',
        'cpf',
        'telefone',
        'email',
        'senha_hash',
        'diretor_id',
        'unidade_id'
    ];

    protected $useTimestamps = true;

    protected $validationRules = [
        'nome'  => 'required|min_length[3]|max_length[255]',
        'cpf'   => 'required|exact_length[11]|is_unique[admins.cpf,id_admin,{id_admin}]',
        'email' => 'required|valid_email|is_unique[admins.email,id_admin,{id_admin}]',
        'senha' => 'required|min_length[6]',
    ];

    protected $validationMessages = [
        'cpf' => [
            'exact_length' => 'O CPF deve ter 11 dígitos.',
            'is_unique'    => 'Este CPF já está cadastrado.'
        ],
    ];
}
