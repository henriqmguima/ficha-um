<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Registrar Posto de Saúde</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/registrar.css') ?>">
    <link rel="shortcut icon" href="<?= base_url('assets/images/logo/favicon.ico') ?>" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body class="login-body">

    <div class="login-container">
        <div class="login-box">
            <h2>Registrar <span class="logo">Unidade</span></h2>
            <p class="subtitle">Cadastre sua unidade e o administrador principal</p>

            <?php if (session()->getFlashdata('erro')) : ?>
                <div class="erro-msg"><?= session()->getFlashdata('erro') ?></div>
            <?php endif; ?>

            <form action="<?= site_url('registrar-posto') ?>" method="post">
                <h3>Dados da Unidade</h3>

                <label>CNES</label>
                <input type="text" name="cnes" required>

                <label>Nome da Unidade</label>
                <input type="text" name="nome" required>

                <label>Endereço</label>
                <input type="text" name="endereco" required>

                <label>CEP</label>
                <input type="text" name="cep" required>

                <label>Cidade</label>
                <input type="text" name="cidade" required>

                <label>Estado</label>
                <input type="text" name="estado" required>



                <h3>Diretor Responsável</h3>

                <label>Nome do Diretor</label>
                <input type="text" name="diretor_nome" required>

                <label>CPF do Diretor</label>
                <input type="text" name="diretor_cpf" required>

                <label>Email do Diretor</label>
                <input type="email" name="diretor_email" required>

                <label>Senha do Diretor</label>
                <input type="password" name="diretor_senha" required>

                <button type="submit">Cadastrar Posto</button>
            </form>

            <div style="margin-top: 20px; text-align: center;">
                <a href="<?= site_url('login') ?>">← Voltar para o login</a>
            </div>
        </div>
    </div>

</body>

</html>