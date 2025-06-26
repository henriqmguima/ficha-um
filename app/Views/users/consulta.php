<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="<?php echo base_url('assets/images/logo/favicon.ico'); ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/users/consulta.css') ?>">
    <script defer src="<?= base_url('assets/js/users/consulta.js') ?>"></script>
    <title>Sua Ficha</title>
    <script>
    </script>
</head>
<body>
    <h1>Olá, <?= esc(session('usuarioLogado')['nome']) ?>!</h1>

    <div id="conteudo">
        <p>Carregando informações da sua ficha...</p>
    </div>

    <p><a href="<?= site_url('logout') ?>">Sair</a></p>
</body>
</html>
