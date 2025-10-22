<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliação de Triagem | Ficha Um</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/index.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/avaliar.css') ?>">
    <link rel="shortcut icon" href="<?php echo base_url('assets/images/logo/favicon.ico'); ?>" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="admin-body">
    <button class="menu-toggle" onclick="toggleSidebar()">
        <i class="fa fa-bars"></i>
    </button>

    <div class="layout">
        <?= view('layouts/aside') ?>

        <main class="main-content">
            <h1>Avaliação de Triagem</h1>

            <section class="ficha-info">
                <h3>👤 Dados do Paciente</h3>
                <p><strong>Nome:</strong> <?= esc($ficha['nome_paciente']) ?></p>
                <p><strong>CPF:</strong> <?= esc($ficha['cpf']) ?></p>
                <p><strong>Tipo de Atendimento:</strong> <?= esc($ficha['tipo_atendimento'] ?? '—') ?></p>
                <p><strong>Status Atual:</strong> <?= esc($ficha['status']) ?></p>
            </section>

            <form action="<?= site_url('admin/fichas/salvarAvaliacao/' . $ficha['id']) ?>" method="post" class="form-avaliacao">
                <?= csrf_field() ?>

                <h3>Sinais Vitais</h3>
                <div class="form-group">
                    <label>Temperatura (°C):</label>
                    <input type="number" step="0.1" name="temperatura" required>
                </div>

                <div class="form-group">
                    <label>Pressão Arterial (mmHg):</label>
                    <input type="text" name="pressao" placeholder="Ex: 120/80" required>
                </div>

                <div class="form-group">
                    <label>Frequência Cardíaca (bpm):</label>
                    <input type="number" name="frequencia" required>
                </div>

                <h3>Sintomas</h3>
                <textarea name="sintomas" rows="4" placeholder="Descreva os sintomas do paciente..." required></textarea>

                <h3>Classificação de Risco (Protocolo de Manchester)</h3>
                <div class="protocolo-manchester">
                    <label class="radio-color vermelho">
                        <input type="radio" name="prioridade_manchester" value="vermelho" required>
                        Vermelho — Emergência (atendimento imediato)
                    </label>

                    <label class="radio-color laranja">
                        <input type="radio" name="prioridade_manchester" value="laranja">
                        Laranja — Muito Urgente (até 10 min)
                    </label>

                    <label class="radio-color amarelo">
                        <input type="radio" name="prioridade_manchester" value="amarelo">
                        Amarelo — Urgente (até 60 min)
                    </label>

                    <label class="radio-color verde">
                        <input type="radio" name="prioridade_manchester" value="verde">
                        Verde — Pouco Urgente (até 120 min)
                    </label>

                    <label class="radio-color azul">
                        <input type="radio" name="prioridade_manchester" value="azul">
                        Azul — Não Urgente (até 240 min)
                    </label>
                </div>

                <button type="submit" class="btn btn-success mt-3">
                    <i class="fa fa-paper-plane"></i> Salvar Avaliação
                </button>
                <a href="<?= site_url('admin/fichas') ?>" class="btn btn-secondary mt-3">Cancelar</a>
            </form>
        </main>
    </div>
</body>

</html>