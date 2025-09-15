<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    protected $returnType = 'array';

    protected $allowedFields = [
        'nome',
        'cpf',
        'cartao_sus',
        'telefone',
        'endereco',
        'email',
        'senha_hash',
        'unidade_id',
        'autenticacao_status',
        'aprovado_por_admin_id',
        'autenticado_em',
    ];



    protected $useTimestamps = true;
    protected $validationRules = [
        'nome'  => 'required|min_length[3]|max_length[255]',
        'cpf'   => 'required|exact_length[11]',
        'email' => 'permit_empty|valid_email',
        'senha_hash' => 'required|min_length[6]',
    ];


    protected $validationMessages = [
        'cpf' => [
            'exact_length' => 'O CPF deve ter 11 dígitos.',
            'is_unique'    => 'Este CPF já está cadastrado.'
        ],
        'email' => [
            'valid_email' => 'E-mail inválido.',
            'is_unique'   => 'Este e-mail já está cadastrado.'
        ],
    ];
}
