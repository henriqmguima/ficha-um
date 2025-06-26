<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Ficha Um</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/login.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <script src="<?= base_url('assets/js/senha.js') ?>"></script>
    <link rel="shortcut icon" href="<?php echo base_url('assets/images/logo/favicon.ico'); ?>" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-box">
            <h2>Bem-vindo ao <span class="logo">Ficha<span class="um">Um</span></span></h2>
            <p class="subtitle">Acesse sua conta para continuar</p>

            <?php if (session()->getFlashdata('erro')) : ?>
                <div class="erro-msg"><?= session()->getFlashdata('erro') ?></div>
            <?php endif; ?>

            <form method="post" action="<?= site_url('login/autenticar') ?>">
                <label for="cpf">CPF</label>
                <input type="text" name="cpf" id="cpf" required placeholder="Digite seu CPF" maxlength="14">

                <label for="senha">Senha</label>
                <div class="senha-wrapper">
                    <input type="password" name="senha" id="senha" required placeholder="Digite sua senha">
                    <i id="toggleSenha" class="fa-solid fa-eye toggle-senha" title="Mostrar/ocultar senha"></i>
                </div>

                <button type="submit">Entrar</button>
            </form>

            <div class="registro-link">
                <p>Deseja Registrar uma unidade?</p>
                <a href="<?= site_url('registrar-posto') ?>" class="btn-secundario">Registrar nova unidade</a>
            </div>
        </div>
    </div>
</body>
</html>
