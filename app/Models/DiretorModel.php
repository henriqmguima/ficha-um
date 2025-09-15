<?php

namespace App\Models;

use CodeIgniter\Model;

class DiretorModel extends Model
{
    protected $table = 'diretores';
    protected $primaryKey = 'id_diretor';
    protected $returnType = 'array';

    protected $allowedFields = [
        'nome',
        'cpf',
        'telefone',
        'email',
        'senha_hash',
        'unidade_id'
    ];

    protected $useTimestamps = true;

    protected $validationMessages = [
        'nome' => [
            'required'   => 'O nome do diretor precisa ser preenchido.',
            'min_length' => 'O nome deve ter pelo menos 3 caracteres.'
        ],
        'cpf' => [
            'required'   => 'O CPF do diretor precisa ser preenchido.',
            'exact_length' => 'O CPF deve ter 11 dígitos (somente números).',
            'is_unique'  => 'Este CPF já está cadastrado.'
        ],
        'email' => [
            'required'    => 'O email do diretor precisa ser preenchido.',
            'valid_email' => 'Digite um email válido.',
            'is_unique'   => 'Este email já está em uso.'
        ],
        'senha' => [
            'required'   => 'A senha do diretor precisa ser preenchida.',
            'min_length' => 'A senha deve ter no mínimo 6 caracteres.'
        ]
    ];
}
