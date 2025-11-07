<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Ficha | Ficha Um</title>

    <link rel="shortcut icon" href="<?= base_url('assets/images/logo/favicon.ico') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url('assets/css/users/consulta.css') ?>">
    <script defer src="<?= base_url('assets/js/users/consulta.js') ?>"></script>
</head>

<body>
    <header class="topbar">
        <div class="logo-area">
            <img src="<?= base_url('assets/images/logo/logo_ficha_um.png') ?>" alt="Ficha Um">
        </div>

        <div class="user-info">
            <span class="user-name"><strong><?= esc(session('usuarioLogado')['nome']) ?></strong></span>
            <a href="<?= site_url('logout') ?>" class="logout-btn">
                <i class="fa fa-sign-out-alt"></i> Sair
            </a>
        </div>
    </header>

    <main class="main-container">
        <section id="conteudo" class="ficha-section">
            <div class="loading">
                <p>🔄 Carregando informações da sua ficha...</p>
            </div>
        </section>
    </main>

    <footer class="footer">
        <p>© <?= date('Y') ?> Ficha Um — Sistema de Fichas Digitais</p>
    </footer>

    <script>
        const usuarioLogado = {
            nome: "<?= esc(session('usuarioLogado')['nome']) ?>",
            cpf: "<?= esc(session('usuarioLogado')['cpf']) ?>",
            postoNome: "<?= esc($postoNome ?? 'Posto não informado') ?>"
        };
    </script>

    <!-- Ícones Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</body>

</html>