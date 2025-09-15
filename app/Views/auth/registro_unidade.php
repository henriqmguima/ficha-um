<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Registrar Unidade | Ficha Um</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/registrar.css') ?>">
    <link rel="shortcut icon" href="<?= base_url('assets/images/logo/favicon.ico') ?>" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body class="login-body">

    <div class="login-container">
        <div class="login-box">
            <h2>Registrar <span class="logo">Unidade</span></h2>
            <p class="subtitle">Cadastre sua unidade e o diretor responsável</p>

            <form action="<?= site_url('registrar-unidade') ?>" method="post" novalidate>
                <?= csrf_field() ?>
                <div class="form-sections">

                    <!-- Bloco da Unidade -->
                    <div class="form-box">

                        <h3>Dados da Unidade</h3>

                        <label for="cnes">CNES</label>
                        <input type="text" id="cnes" name="cnes" value="<?= old('cnes') ?>" required>
                        <?php if (isset($validation) && $validation->hasError('cnes')): ?>
                            <small class="erro-msg"><?= $validation->getError('cnes') ?></small>
                        <?php endif; ?>

                        <label for="nome">Nome da Unidade</label>
                        <input type="text" id="nome" name="nome" value="<?= old('nome') ?>" required minlength="3">
                        <?php if (isset($validation) && $validation->hasError('nome')): ?>
                            <small class="erro-msg"><?= $validation->getError('nome') ?></small>
                        <?php endif; ?>
                        <label for="logradouro">Logradouro</label>
                        <input type="text" id="logradouro" name="logradouro" value="<?= old('logradouro') ?>" required>
                        <?php if (isset($validation) && $validation->hasError('logradouro')): ?>
                            <small class="erro-msg"><?= $validation->getError('logradouro') ?></small>
                        <?php endif; ?>
                        <label for="bairro">Bairro</label>
                        <input type="text" id="bairro" name="bairro" value="<?= old('bairro') ?>" required>
                        <?php if (isset($validation) && $validation->hasError('bairro')): ?>
                            <small class="erro-msg"><?= $validation->getError('bairro') ?></small>
                        <?php endif; ?>
                        <label for="numero">Número</label>
                        <input type="text" id="numero" name="numero" value="<?= old('numero') ?>" required>
                        <?php if (isset($validation) && $validation->hasError('numero')): ?>
                            <small class="erro-msg"><?= $validation->getError('numero') ?></small>
                        <?php endif; ?>
                        <label for="cep">CEP</label>
                        <input type="text" id="cep" name="cep" value="<?= old('cep') ?>" required>
                        <?php if (isset($validation) && $validation->hasError('cep')): ?>
                            <small class="erro-msg"><?= $validation->getError('cep') ?></small>
                        <?php endif; ?>
                        <label for="municipio">Cidade</label>
                        <input type="text" id="municipio" name="municipio" value="<?= old('municipio') ?>" required>
                        <?php if (isset($validation) && $validation->hasError('municipio')): ?>
                            <small class="erro-msg"><?= $validation->getError('municipio') ?></small>
                        <?php endif; ?>
                        <label for="uf">Estado</label>
                        <input type="text" id="uf" name="uf" value="<?= old('uf') ?>" maxlength="2" required placeholder="UF">
                        <?php if (isset($validation) && $validation->hasError('uf')): ?>
                            <small class="erro-msg"><?= $validation->getError('uf') ?></small>
                        <?php endif; ?>

                        <label for="telefone">Telefone</label>
                        <input type="text" id="telefone" name="telefone" value="<?= old('telefone') ?>" required>
                        <?php if (isset($validation) && $validation->hasError('telefone')): ?>
                            <small class="erro-msg"><?= $validation->getError('telefone') ?></small>
                        <?php endif; ?>

                    </div>

                    <!-- Bloco do Diretor -->
                    <div class="form-box">
                        <h3>Diretor Responsável</h3>

                        <label for="diretor_nome">Nome completo do Diretor</label>
                        <input type="text" id="diretor_nome" name="diretor_nome" value="<?= old('diretor_nome') ?>" required minlength="3">
                        <?php if (isset($validation) && $validation->hasError('diretor_nome')): ?>
                            <small class="erro-msg"><?= $validation->getError('diretor_nome') ?></small>
                        <?php endif; ?>

                        <label for="diretor_cpf">CPF do Diretor</label>
                        <input type="text" id="diretor_cpf" name="diretor_cpf" value="<?= old('diretor_cpf') ?>" required>
                        <?php if (isset($validation) && $validation->hasError('diretor_cpf')): ?>
                            <small class="erro-msg"><?= $validation->getError('diretor_cpf') ?></small>
                        <?php endif; ?>

                        <label for="diretor_email">Email do Diretor</label>
                        <input type="email" id="diretor_email" name="diretor_email" value="<?= old('diretor_email') ?>" required>
                        <?php if (isset($validation) && $validation->hasError('diretor_email')): ?>
                            <small class="erro-msg"><?= $validation->getError('diretor_email') ?></small>
                        <?php endif; ?>

                        <label for="diretor_telefone">Telefone do Diretor</label>
                        <input type="text" id="diretor_telefone" name="diretor_telefone" value="<?= old('diretor_telefone') ?>" required>
                        <?php if (isset($validation) && $validation->hasError('diretor_telefone')): ?>
                            <small class="erro-msg"><?= $validation->getError('diretor_telefone') ?></small>
                        <?php endif; ?>

                        <label for="diretor_senha">Senha do Diretor</label>
                        <input type="password" id="diretor_senha" name="diretor_senha" required minlength="6">
                        <?php if (isset($validation) && $validation->hasError('diretor_senha')): ?>
                            <small class="erro-msg"><?= $validation->getError('diretor_senha') ?></small>
                        <?php endif; ?>

                        <label for="confirmar_senha">Confirmar Senha</label>
                        <input type="password" id="confirmar_senha" name="confirmar_senha" required minlength="6">
                        <?php if (isset($validation) && $validation->hasError('confirmar_senha')): ?>
                            <small class="erro-msg"><?= $validation->getError('confirmar_senha') ?></small>
                        <?php endif; ?>
                    </div>
                    <button type="submit">Cadastrar Unidade</button>
            </form>


            <div style="margin-top: 20px; text-align: center;">
                <a href="<?= site_url('login') ?>">← Voltar para o login</a>
            </div>
        </div>
    </div>

</body>

</html>