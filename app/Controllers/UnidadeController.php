<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UnidadeModel;
use App\Models\DiretorModel;

class UnidadeController extends BaseController
{
    public function create()
    {
        return view('auth/registro_unidade', [
            'validation' => \Config\Services::validation()
        ]);
    }
    public function store()
    {
        helper(['form', 'text']);

        $rules = [
            'cnes'             => 'required|is_unique[unidades.cnes]',
            'nome'             => 'required|min_length[3]',
            'cep'              => 'required|regex_match[/^\d{5}-?\d{3}$/]',
            'municipio'        => 'required|min_length[3]',
            'logradouro'       => 'required',
            'bairro'           => 'required',
            'numero'           => 'required',
            'telefone'         => 'required|min_length[10]',
            'uf'               => 'required|exact_length[2]',
            'diretor_nome'     => 'required|min_length[3]',
            'diretor_email'    => 'required|valid_email|is_unique[diretores.email]',
            'diretor_cpf'      => 'required|is_unique[diretores.cpf]',
            'diretor_telefone' => 'required|min_length[11]',
            'diretor_senha'    => [
                'rules'  => 'required|min_length[6]|regex_match[/(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z])(?=.*\W).+$/]',
                'errors' => [
                    'regex_match' => 'A senha deve conter pelo menos uma letra maiúscula, uma minúscula, um número e um caractere especial.'
                ]
            ],
            'confirmar_senha' => 'required|matches[diretor_senha]'
        ];

        $messages = [
            'cnes' => [
                'required'   => 'O CNES precisa ser preenchido.',
                'is_unique'  => 'Este CNES já está registrado.'
            ],
            'nome' => [
                'required'   => 'O nome da unidade precisa ser preenchido.',
                'min_length' => 'O nome deve ter pelo menos 3 caracteres.'
            ],
            'cep' => [
                'required'    => 'O CEP precisa ser preenchido.',
                'regex_match' => 'Digite um CEP válido (ex: 12345-678).'
            ],
            'municipio' => [
                'required'   => 'A cidade precisa ser preenchida.',
                'min_length' => 'A cidade deve ter pelo menos 3 caracteres.'
            ],
            'uf' => [
                'required'     => 'O estado (UF) precisa ser preenchido.',
                'exact_length' => 'A UF deve ter exatamente 2 letras.'
            ],
            'diretor_email' => [
                'is_unique'   => 'Este email já está registrado.'
            ],
            'diretor_cpf' => [
                'is_unique'   => 'Este CPF já está registrado.'
            ],
            'confirmar_senha' => [
                'matches' => 'As senhas não conferem.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return view('auth/registro_unidade', [
                'validation' => $this->validator
            ]);
        }

        $unidadeModel = new UnidadeModel();

        $unidadeData = [
            'cnes'       => $this->request->getPost('cnes'),
            'nome'       => $this->request->getPost('nome'),
            'logradouro' => $this->request->getPost('logradouro'),
            'cep'        => $this->request->getPost('cep'),
            'municipio'  => $this->request->getPost('municipio'),
            'uf'         => strtoupper($this->request->getPost('uf')),
            'telefone'   => $this->request->getPost('telefone'),
            'bairro'     => $this->request->getPost('bairro'),
            'numero'     => $this->request->getPost('numero'),
        ];

        $unidadeId = $unidadeModel->insert($unidadeData);

        if (!$unidadeId) {
            return redirect()->back()->with('erro', 'Erro ao salvar a unidade.')->withInput();
        }

        $diretorModel = new DiretorModel();
        $senhaHash = password_hash($this->request->getPost('diretor_senha'), PASSWORD_DEFAULT);

        $diretorData = [
            'unidade_id' => $unidadeId,
            'cpf'        => $this->request->getPost('diretor_cpf'),
            'nome'       => $this->request->getPost('diretor_nome'),
            'email'      => $this->request->getPost('diretor_email'),
            'telefone'   => $this->request->getPost('diretor_telefone'),
            'senha_hash' => $senhaHash,
            'is_active'  => 1,
        ];

        $diretorId = $diretorModel->insert($diretorData);

        if (!$diretorId) {
            $unidadeModel->delete($unidadeId);
            return redirect()->back()->with('erro', 'Erro ao criar o diretor. Unidade removida.')->withInput();
        }

        return redirect()->to('/login')->with('success', 'Unidade registrada com sucesso. Faça login para continuar.');

        return redirect()->to('/login')->with('success', 'Unidade registrada com sucesso. Faça login para continuar.');
    }
}
