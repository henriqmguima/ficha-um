<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fila de Triagem | Ficha Um</title>

    <!-- CSS principal -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/index.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/modal.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/modal-ficha.css') ?>">

    <link rel="shortcut icon" href="<?= base_url('assets/images/logo/favicon.ico') ?>" type="image/x-icon">

    <!-- Ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Scripts -->
    <script defer src="<?= base_url('assets/js/admin/modal_create_usuario.js') ?>"></script>
    <script defer src="<?= base_url('assets/js/admin/modal_create_ficha.js') ?>"></script>
    <script>
        const API_LISTAR_FICHAS = "<?= site_url('api/admin/fichas/api-listar') ?>";
    </script>
    <script src="<?= base_url('assets/js/admin/fila_admin.js') ?>"></script>


</head>

<body class="admin-body">
    <button class="menu-toggle" onclick="toggleSidebar()">
        <i class="fa fa-bars"></i>
    </button>

    <div class="layout">
        <?= view('layouts/aside') ?>

        <!-- CONTEÚDO PRINCIPAL -->
        <main class="main-content">
            <h1>Fila de Triagem - Administração</h1>



            <!-- TABELA -->
            <div class="tabela-wrapper">
                <table id="tabela-fichas">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Paciente</th>
                            <th>Serviço</th>
                            <th>Status</th>
                            <th>Posição</th>
                            <th>Data</th>
                            <th>Tempo de Espera</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($fichas)): ?>
                            <tr>
                                <td colspan="8">Nenhuma ficha encontrada.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($fichas as $ficha): ?>
                                <tr>
                                    <td><?= esc($ficha['id']) ?></td>
                                    <td><?= esc($ficha['nome_paciente']) ?></td>
                                    <td><?= esc($ficha['tipo_atendimento'] ?? '—') ?></td>
                                    <td>
                                        <span class="status-badge status-<?= esc($ficha['status']) ?>">
                                            <?= ucfirst($ficha['status']) ?>
                                        </span>
                                    </td>
                                    <td><?= esc($ficha['posicao'] ?? '—') ?></td>
                                    <td><?= esc($ficha['data_formatada'] ?? date('d/m/Y H:i', strtotime($ficha['criado_em']))) ?></td>
                                    <td><?= esc($ficha['tempo_espera'] ?? '—') ?></td>
                                    <td class="acoes">
                                        <?php if ($ficha['status'] === 'aguardando'): ?>
                                            <a href="<?= site_url('admin/fichas/avaliar/' . $ficha['id']) ?>" title="Realizar Triagem">
                                                <i class="fa fa-notes-medical" style="color:#2563eb;"></i>
                                            </a>
                                        <?php elseif ($ficha['status'] === 'acolhido'): ?>
                                            <span class="text-muted">Aguardando médico</span>
                                        <?php elseif ($ficha['status'] === 'chamado'): ?>
                                            <span class="text-warning">Em atendimento</span>
                                        <?php else: ?>
                                            <span class="text-success">✔</span>
                                        <?php endif; ?>

                                        <a href="javascript:void(0);" onclick="abrirModalExclusao(<?= $ficha['id'] ?>)" title="Excluir">
                                            <i class="fa fa-trash" style="color:#b91c1c;"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <footer class="footer">
                Logado como <strong><?= esc(session('usuarioLogado')['nome']) ?></strong>
            </footer>
            <!-- Modal de Confirmação -->
            <div id="modalConfirmacao" class="modal-overlay-ficha" style="display: none;">
                <div class="modal-content">
                    <h3>🗑️ Confirmar exclusão</h3>
                    <p>Tem certeza de que deseja excluir esta ficha? Esta ação não pode ser desfeita.</p>
                    <div class="modal-actions">
                        <button id="btnCancelar" class="btn-cancelar">Cancelar</button>
                        <button id="btnConfirmar" class="btn-confirmar">Excluir</button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <?= view('admin/fichas/modal_create', ['usuarios' => $usuarios ?? []]) ?>
    <?= view('admin/modal_create_usuario') ?>
    <script>
        let idFichaSelecionada = null;

        // Abre o modal
        function abrirModalExclusao(idFicha) {
            idFichaSelecionada = idFicha;
            document.getElementById("modalConfirmacao").style.display = "flex";
        }

        // Fecha o modal
        document.getElementById("btnCancelar").addEventListener("click", () => {
            document.getElementById("modalConfirmacao").style.display = "none";
            idFichaSelecionada = null;
        });

        // Confirma exclusão
        document.getElementById("btnConfirmar").addEventListener("click", () => {
            if (!idFichaSelecionada) return;
            window.location.href = `/admin/fichas/delete/${idFichaSelecionada}`;
        });
    </script>

</body>

</html>