<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Detalhes da Ficha | Ficha Um</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/medicos/ver_ficha.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="admin-body">
    <div class="layout">

        <main class="main-content">
            <h1>📋 Detalhes da Ficha</h1>

            <?php if (isset($ficha) && $ficha): ?>
                <section class="ficha-detalhes">
                    <h3>👤 Dados do Paciente</h3>
                    <p><strong>Nome:</strong> <?= esc($ficha['nome_paciente']) ?></p>
                    <p><strong>CPF:</strong> <?= esc($ficha['cpf'] ?? '—') ?></p>
                    <p><strong>Tipo de Atendimento:</strong> <?= esc($ficha['tipo_atendimento'] ?? '—') ?></p>
                    <p class="status-p"><strong>Status Atual:</strong>
                        <span class="status-badge status-<?= esc($ficha['status']) ?>">
                            <?= ucfirst($ficha['status']) ?>
                        </span>
                    </p>
                </section>

                <section class="ficha-triagem">
                    <h3>🩺 Informações de Triagem</h3>
                    <?php
                    $sinais = [];
                    if (!empty($ficha['sinais_vitais'])) {
                        $sinais = json_decode($ficha['sinais_vitais'], true);
                    }
                    ?>
                    <p><strong>Temperatura:</strong> <?= esc($sinais['temperatura'] ?? '—') ?> °C</p>
                    <p><strong>Pressão Arterial:</strong> <?= esc($sinais['pressao'] ?? '—') ?></p>
                    <p><strong>Frequência Cardíaca:</strong> <?= esc($sinais['frequencia'] ?? '—') ?> bpm</p>

                    <p><strong>Sintomas:</strong> <?= esc($ficha['sintomas'] ?? '—') ?></p>
                    <p class="status-p"><strong>Prioridade (Manchester):</strong>
                        <span class="badge prioridade-<?= esc($ficha['prioridade_manchester'] ?? 'indefinida') ?>">
                            <?= ucfirst($ficha['prioridade_manchester'] ?? 'Indefinida') ?>
                        </span>
                    </p>
                </section>

                <a href="<?= site_url('medico') ?>" class="btn btn-primary">⬅ Voltar à fila</a>

            <?php else: ?>
                <p>Ficha não encontrada.</p>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>