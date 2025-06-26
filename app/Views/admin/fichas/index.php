<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fila de Atendimento | Ficha Um</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/index.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/modal.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="shortcut icon" href="<?php echo base_url('assets/images/logo/favicon.ico'); ?>" type="image/x-icon">
    <script defer src="<?= base_url('assets/js/admin/modal_create_usuario.js') ?>"></script>
    <script defer src="<?= base_url('assets/js/admin/modal_create_ficha.js') ?>"></script>
    <script defer src="<?= base_url('assets/js/admin/fila_admin.js') ?>"></script>

</head>
<body class="admin-body">
    <button class="menu-toggle" onclick="toggleSidebar()">
        <i class="fa fa-bars"></i>
    </button>

    <div class="layout">
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

        <!-- CONTEÚDO PRINCIPAL -->
        <main class="main-content">
            <h1>Fila de Atendimento - Administração</h1>

            <form method="get" action="<?= site_url('admin/fichas') ?>" class="filtro-form">
                <label for="status">Filtrar por status:</label>
                <select name="status" id="status" onchange="this.form.submit()">
                    <option value="" <?= $statusAtual == 'todos' ? 'selected' : '' ?>>Todos</option>
                    <option value="aguardando" <?= $statusAtual == 'aguardando' ? 'selected' : '' ?>>Aguardando</option>
                    <option value="em_atendimento" <?= $statusAtual == 'em_atendimento' ? 'selected' : '' ?>>Em Atendimento</option>
                    <option value="atendido" <?= $statusAtual == 'atendido' ? 'selected' : '' ?>>Atendido</option>
                </select>
            </form>

            <?php if (!empty($fichas)) : ?>
                <div class="tabela-wrapper">
                    <table class="tabela-fichas">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Tipo</th>
                                <th>Status</th>
                                <th>Posição</th>
                                <th>Data</th>
                                <th>Tempo de Espera</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($fichas as $ficha) : ?>
                                <tr>
                                    <td><?= esc($ficha['id']) ?></td>
                                    <td><?= esc($ficha['nome_paciente']) ?></td>
                                    <td><?= esc($ficha['tipo_atendimento']) ?></td>
                                    <td><?= esc($ficha['status']) ?></td>
                                    <td><?= esc($ficha['posicao']) ?></td>
                                    <td><?= esc(date('d/m/Y H:i', strtotime($ficha['criado_em']))) ?></td>
                                    <td>
                                        <?php if ($ficha['status'] === 'aguardando'): ?>
                                            <span class="tempo-espera" data-inicio="<?= $ficha['criado_em_timestamp'] ?>" id="espera-<?= $ficha['id'] ?>">
                                                <?= esc($ficha['tempo_espera']) ?>
                                            </span>
                                        <?php else: ?>
                                            —
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($ficha['status'] == 'aguardando'): ?>
                                            <a href="<?= site_url('admin/fichas/status/' . $ficha['id'] . '/em_atendimento') ?>" title="Atender">
                                                <i class="fa fa-stethoscope"></i>
                                            </a>
                                        <?php elseif ($ficha['status'] == 'em_atendimento'): ?>
                                            <a href="<?= site_url('admin/fichas/status/' . $ficha['id'] . '/atendido') ?>" title="Finalizar">
                                                <i class="fa fa-check"></i>
                                            </a>
                                        <?php else: ?>
                                            <span title="Atendido">✔</span>
                                        <?php endif; ?>
                                        <a href="<?= site_url('admin/fichas/delete/' . $ficha['id']) ?>" onclick="return confirm('Tem certeza que deseja excluir?')" title="Excluir">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <p>Nenhuma ficha cadastrada.</p>
            <?php endif; ?>

            <footer class="footer">
                Logado como <strong><?= esc(session('usuarioLogado')['nome']) ?></strong>
            </footer>
        </main>
    </div>
    <?= view('admin/fichas/modal_create', ['usuarios' => $usuarios]) ?>
    <?= view('admin/modal_create_usuario') ?>
</body>
</html>
