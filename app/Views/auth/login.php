<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Ficha Um</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/login.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <script src="<?= base_url('assets/js/senha.js') ?>"></script>
    <link rel="shortcut icon" href="<?= base_url('assets/images/logo/favicon.ico'); ?>" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="login-body">
    <div class="login-container">
        <div class="login-box">
            <h2>Bem-vindo ao <span class="logo">Ficha<span class="um">Um</span></span></h2>
            <p class="subtitle">Acesse sua conta para continuar</p>

            <?php if (session()->getFlashdata('erro')) : ?>
                <div class="erro-msg"><?= esc(session()->getFlashdata('erro')) ?></div>
            <?php endif; ?>

            <form method="post" action="<?= site_url('login/autenticar') ?>" novalidate>
                <?= csrf_field() ?>

                <label for="cpf">CPF</label>
                <input type="text"
                    name="cpf"
                    id="cpf"
                    required
                    pattern="\d{3}\.?\d{3}\.?\d{3}-?\d{2}"
                    title="Digite um CPF válido"
                    placeholder="Digite seu CPF"
                    maxlength="14">

                <label for="senha">Senha</label>
                <div class="senha-wrapper">
                    <input type="password"
                        name="senha"
                        id="senha"
                        required
                        minlength="6"
                        placeholder="Digite sua senha">
                    <i id="toggleSenha" class="fa-solid fa-eye toggle-senha" title="Mostrar/ocultar senha"></i>
                </div>

                <button type="submit">Entrar</button>
            </form>

            <div class="registro-link">
                <p>Não tem uma conta?</p>
                <a href="<?= site_url('registrar-usuario') ?>" class="btn-secundario">Registrar usuário</a>
            </div>

            <div class="registro-link">
                <p>Ou deseja registrar uma nova unidade?</p>
                <a href="<?= site_url('registrar-unidade') ?>" class="btn-secundario">Registrar unidade</a>
            </div>
        </div>
    </div>
</body>

</html>