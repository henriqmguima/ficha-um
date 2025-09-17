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

    // 🔹 Regras de validação
    protected $validationRules = [
        'nome'        => 'required|min_length[3]|max_length[255]',
        'cpf'         => 'required|exact_length[11]|is_unique[usuarios.cpf]',
        'cartao_sus'  => 'required|min_length[15]|max_length[20]|is_unique[usuarios.cartao_sus]',
        'endereco'    => 'required|min_length[5]|max_length[255]',
        'telefone'    => 'required|min_length[10]|max_length[20]',
        'email'       => 'required|valid_email|is_unique[usuarios.email]',
        'senha_hash'  => 'required|min_length[6]',
    ];

    // 🔹 Mensagens personalizadas
    protected $validationMessages = [
        'nome' => [
            'required'   => 'O campo Nome é obrigatório.',
            'min_length' => 'O Nome deve ter no mínimo 3 caracteres.',
            'max_length' => 'O Nome não pode ultrapassar 255 caracteres.',
        ],
        'cpf' => [
            'required'     => 'O campo CPF é obrigatório.',
            'exact_length' => 'O CPF deve conter exatamente 11 dígitos.',
            'is_unique'    => 'Este CPF já está cadastrado.',
        ],
        'cartao_sus' => [
            'required'   => 'O campo Cartão SUS é obrigatório.',
            'min_length' => 'O Cartão SUS deve ter pelo menos 15 dígitos.',
            'max_length' => 'O Cartão SUS não pode ultrapassar 20 dígitos.',
            'is_unique'  => 'Este Cartão SUS já está cadastrado.',
        ],
        'endereco' => [
            'required'   => 'O campo Endereço é obrigatório.',
            'min_length' => 'O Endereço deve ter pelo menos 5 caracteres.',
            'max_length' => 'O Endereço não pode ultrapassar 255 caracteres.',
        ],
        'telefone' => [
            'required'   => 'O campo Telefone é obrigatório.',
            'min_length' => 'O Telefone deve ter pelo menos 10 caracteres.',
            'max_length' => 'O Telefone não pode ultrapassar 20 caracteres.',
        ],
        'email' => [
            'required'    => 'O campo E-mail é obrigatório.',
            'valid_email' => 'Informe um e-mail válido.',
            'is_unique'   => 'Este e-mail já está cadastrado.',
        ],
        'senha_hash' => [
            'required'   => 'A senha gerada não foi definida corretamente.',
            'min_length' => 'A senha deve ter no mínimo 6 caracteres.',
        ],
    ];
}
