<link rel="stylesheet" href="<?= base_url('assets/css/layouts.css') ?>">

<!-- MENU LATERAL -->
<aside class="sidebar">
    <div class="logo">
        <img src="<?= base_url('assets/images/logo/logo_ficha_um.png') ?>" alt="Ficha Um">
    </div>
    <nav class="menu">
        <a href="<?= site_url('admin/fichas') ?>" class="active"><i class="fa fa-list"></i> Fichas</a>
        <a href="#" onclick="abrirModal()"><i class="fa fa-plus"></i> Nova Ficha</a>
        <a href="#" onclick="abrirModal_Usuario()"><i class="fa fa-user-plus"></i> Novo Usuário</a>
        <a href="<?= site_url('logout') ?>"><i class="fa fa-sign-out-alt"></i> Sair</a>
    </nav>
</aside>