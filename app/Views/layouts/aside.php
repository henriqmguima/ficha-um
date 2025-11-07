<link rel="stylesheet" href="<?= base_url('assets/css/layouts.css') ?>">

<!-- MENU LATERAL -->
<aside class="sidebar">
    <div class="logo">
        <img src="<?= base_url('assets/images/logo/logo_ficha_um.png') ?>" alt="Ficha Um">
    </div>

    <?php
    $usuario = session('usuarioLogado');
    $role = $usuario['role'] ?? 'user';
    $rotaAtual = uri_string(); // ex: "medico" ou "admin/fichas"
    ?>

    <nav class="menu">
        <?php if ($role === 'admin' || $role === 'diretor'): ?>
            <!-- Menu de administrador -->
            <a href="<?= site_url('admin/fichas') ?>" class="<?= $rotaAtual === 'admin/fichas' ? 'active' : '' ?>">
                <i class="fa fa-list"></i> Fichas
            </a>
            <a href="#" onclick="abrirModal()">
                <i class="fa fa-plus"></i> Nova Ficha
            </a>
            <a href="#" onclick="abrirModal_Usuario()">
                <i class="fa fa-user-plus"></i> Novo Usuário
            </a>
            <a href="<?= site_url('logout') ?>">
                <i class="fa fa-sign-out-alt"></i> Sair
            </a>

        <?php elseif ($role === 'medico'): ?>
            <!-- Menu simplificado do médico -->
            <a href="<?= site_url('medico') ?>" class="<?= $rotaAtual === 'medico' ? 'active' : '' ?>">
                <i class="fa fa-stethoscope"></i> Fila
            </a>
            <a href="<?= site_url('logout') ?>">
                <i class="fa fa-sign-out-alt"></i> Sair
            </a>

        <?php else: ?>

        <?php endif; ?>
    </nav>
</aside>