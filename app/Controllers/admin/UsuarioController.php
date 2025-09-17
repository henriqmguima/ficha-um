<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class UsuarioController extends BaseController
{
    public function store()
    {
        helper(['text', 'form']);
        $usuarioModel = new UsuarioModel();

        $usuarioLogado = session()->get('usuarioLogado');

        // Só Admin ou Diretor pode cadastrar novos usuários
        if (!$usuarioLogado || (!isset($usuarioLogado['id_admin']) && !isset($usuarioLogado['id_diretor']))) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Acesso negado. Apenas administradores ou diretores podem cadastrar usuários.'
            ]);
        }

        if (!isset($usuarioLogado['unidade_id'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unidade não identificada na sessão.'
            ]);
        }

        // Regras de validação
        $rules = [
            'cpf' => [
                'rules'  => 'required|exact_length[11]|is_unique[usuarios.cpf]',
                'errors' => [
                    'required'     => 'O campo CPF é obrigatório.',
                    'exact_length' => 'O CPF deve conter exatamente 11 dígitos.',
                    'is_unique'    => 'Este CPF já está cadastrado no sistema.'
                ]
            ],
            'nome' => [
                'rules'  => 'required|min_length[3]',
                'errors' => [
                    'required'   => 'O campo Nome é obrigatório.',
                    'min_length' => 'O Nome deve ter no mínimo 3 caracteres.'
                ]
            ],
            'cartao_sus' => [
                'rules'  => 'required|min_length[15]|max_length[18]',
                'errors' => [
                    'required'   => 'O campo Cartão SUS é obrigatório.',
                    'min_length' => 'O Cartão SUS deve ter pelo menos 15 caracteres.',
                    'max_length' => 'O Cartão SUS deve ter no máximo 18 caracteres.'
                ]
            ],
            'endereco' => [
                'rules'  => 'required|min_length[5]',
                'errors' => [
                    'required'   => 'O campo Endereço é obrigatório.',
                    'min_length' => 'O Endereço deve ter no mínimo 5 caracteres.'
                ]
            ],
            'telefone' => [
                'rules'  => 'required|min_length[10]|max_length[15]',
                'errors' => [
                    'required'   => 'O campo Telefone é obrigatório.',
                    'min_length' => 'O Telefone deve ter no mínimo 10 caracteres.',
                    'max_length' => 'O Telefone deve ter no máximo 15 caracteres.'
                ]
            ],
            'email' => [
                'rules'  => 'required|valid_email|is_unique[usuarios.email]',
                'errors' => [
                    'required'    => 'O campo E-mail é obrigatório.',
                    'valid_email' => 'Informe um e-mail válido.',
                    'is_unique'   => 'Este e-mail já está cadastrado.'
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => $this->validator->getErrors()
            ]);
        }

        // Gera senha forte
        $senhaPura = $this->gerarSenhaForte(8);
        $senhaHash = password_hash($senhaPura, PASSWORD_DEFAULT);

        // Dados a serem salvos
        $data = [
            'cpf'                  => $this->request->getPost('cpf'),
            'nome'                 => $this->request->getPost('nome'),
            'cartao_sus'           => $this->request->getPost('cartao_sus'),
            'endereco'             => $this->request->getPost('endereco'),
            'telefone'             => $this->request->getPost('telefone'),
            'email'                => $this->request->getPost('email'),
            'senha_hash'           => $senhaHash,
            'unidade_id'           => $usuarioLogado['unidade_id'],
            'autenticacao_status'  => 'pendente',
            'created_at'           => date('Y-m-d H:i:s'),
        ];

        // Salva no banco
        $usuarioModel->insert($data);

        // Resposta para o JS
        return $this->response->setJSON([
            'success' => true,
            'senha'   => $senhaPura
        ]);
    }

    private function gerarSenhaForte($tamanho = 10)
    {
        $maiusculas = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $minusculas = 'abcdefghijklmnopqrstuvwxyz';
        $numeros = '0123456789';
        $simbolos = '!@#$%^&*()-_=+';

        // Garante pelo menos um de cada tipo
        $senha = substr(str_shuffle($maiusculas), 0, 1);
        $senha .= substr(str_shuffle($minusculas), 0, 1);
        $senha .= substr(str_shuffle($numeros), 0, 1);
        $senha .= substr(str_shuffle($simbolos), 0, 1);

        // Preenche o resto da senha
        $todos = $maiusculas . $minusculas . $numeros . $simbolos;
        $senha .= substr(str_shuffle(str_repeat($todos, $tamanho)), 0, $tamanho - 4);

        // Embaralha tudo para não ficar previsível
        return str_shuffle($senha);
    }
}
