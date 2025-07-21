<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="<?= base_url('assets/images/logo/favicon.ico') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <script defer src="<?= base_url('assets/js/users/consulta.js') ?>"></script>

    <link rel="stylesheet" href="<?= base_url('assets/css/users/consulta.css') ?>">

    <title>Sua Ficha</title>
</head>

<body>
    <h1>Olá, <?= esc(session('usuarioLogado')['nome']) ?>!</h1>

    <div id="conteudo">
        <p>Carregando informações da sua ficha...</p>
    </div>

    <p><a href="<?= site_url('logout') ?>">Sair</a></p>
    <script>
        const usuarioLogado = {
            nome: "<?= esc(session('usuarioLogado')['nome']) ?>",
            cpf: "<?= esc(session('usuarioLogado')['cpf']) ?>"
        };
    </script>

</body>

</html>