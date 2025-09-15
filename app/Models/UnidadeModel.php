<?php

namespace App\Models;

use CodeIgniter\Model;

class UnidadeModel extends Model
{
    protected $table = 'unidades';
    protected $primaryKey = 'id_unidade';
    protected $returnType = 'array';

    protected $allowedFields = [
        'nome',
        'cnes',
        'cep',
        'telefone',
        'municipio',
        'uf',
        'logradouro',
        'bairro',
        'numero',
        'diretor_id'
    ];

    protected $useTimestamps = true;

    protected $validationRules = [
        'nome'     => 'required|min_length[3]|max_length[255]',
        'cnes'     => 'required|is_natural_no_zero|max_length[20]',
        'cep'      => 'required|regex_match[/^[0-9]{5}-?[0-9]{3}$/]',
        'telefone' => 'permit_empty|regex_match[/^\(?\d{2}\)? ?9?\d{4}-?\d{4}$/]',
        'municipio' => 'required|min_length[3]|max_length[100]',
        'uf'       => 'required|exact_length[2]',
    ];

    protected $validationMessages = [
        'nome' => [
            'required'    => 'O nome da unidade precisa ser preenchido.',
            'min_length'  => 'O nome deve ter pelo menos 3 caracteres.',
        ],
        'cnes' => [
            'required'            => 'O CNES precisa ser preenchido.',
            'is_natural_no_zero'  => 'O CNES deve ser numérico.',
            'max_length'          => 'O CNES deve ter no máximo 20 dígitos.'
        ],
        'cep' => [
            'required'    => 'O CEP precisa ser preenchido.',
            'regex_match' => 'Digite um CEP válido (ex: 12345-678).'
        ],
        'municipio' => [
            'required'   => 'A cidade precisa ser preenchida.',
            'min_length' => 'A cidade deve ter pelo menos 3 caracteres.',
        ],
        'uf' => [
            'required'     => 'O estado (UF) precisa ser preenchido.',
            'exact_length' => 'A UF deve ter exatamente 2 letras.'
        ],
    ];
}
