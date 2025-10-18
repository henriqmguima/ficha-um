<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fila de Atendimento | Ficha Um</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/index.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="shortcut icon" href="<?php echo base_url('assets/images/logo/favicon.ico'); ?>" type="image/x-icon">
</head>

<body class="admin-body">
    <button class="menu-toggle" onclick="toggleSidebar()">
        <i class="fa fa-bars"></i>
    </button>

    <div class="layout">
        <?= view('layouts/aside') ?>
    </div>


    <div class="container mt-4">
        <h2>Bem-vindo, Dr(a). <?= esc($medico['id']) ?></h2>
        <p><strong>Atendimentos de hoje:</strong> <?= esc($medico['atendimentos_hoje']) ?>/<?= esc($medico['max_atendimentos']) ?></p>

        <h4 class="mt-5">📋 Fichas disponíveis para atendimento</h4>
        <div class="row mt-3">
            <?php if (empty($fichasDisponiveis)): ?>
                <p>Nenhuma ficha disponível no momento.</p>
            <?php else: ?>
                <?php foreach ($fichasDisponiveis as $ficha): ?>
                    <?php
                    $cores = [
                        'vermelho' => 'bg-danger text-white',
                        'laranja'  => 'bg-warning text-dark',
                        'amarelo'  => 'bg-warning-subtle text-dark',
                        'verde'    => 'bg-success text-white',
                        'azul'     => 'bg-primary text-white',
                    ];
                    $classe = $cores[$ficha['prioridade_manchester']] ?? 'bg-light';
                    ?>
                    <div class="col-md-4 mb-3">
                        <div class="card <?= $classe ?> shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><?= esc($ficha['nome_paciente']) ?></h5>
                                <p><strong>Sintomas:</strong> <?= esc($ficha['sintomas']) ?></p>
                                <a href="<?= site_url('medico/assumir/' . $ficha['id']) ?>" class="btn btn-light btn-sm">👩‍⚕️ Assumir</a>
                                <a href="<?= site_url('medico/ver/' . $ficha['id']) ?>" class="btn btn-outline-light btn-sm">Detalhes</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <h4 class="mt-5">🩺 Fichas em atendimento</h4>
        <div class="row mt-3">
            <?php if (empty($fichasEmAtendimento)): ?>
                <p>Você não possui atendimentos em andamento.</p>
            <?php else: ?>
                <?php foreach ($fichasEmAtendimento as $ficha): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card border-success shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><?= esc($ficha['nome_paciente']) ?></h5>
                                <p><strong>Sintomas:</strong> <?= esc($ficha['sintomas']) ?></p>
                                <a href="<?= site_url('medico/finalizar/' . $ficha['id']) ?>" class="btn btn-success btn-sm">✅ Finalizar</a>
                                <a href="<?= site_url('medico/ver/' . $ficha['id']) ?>" class="btn btn-outline-secondary btn-sm">🔍 Ver</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>