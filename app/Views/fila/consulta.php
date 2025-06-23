<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Consultar Ficha</title>
</head>
<body>
    <h1>Consultar Ficha</h1>

    <form method="post" action="<?= site_url('fila/resultado') ?>">
        <label for="id">Digite o ID da sua ficha:</label>
        <input type="number" name="id" id="id" required>
        <button type="submit">Consultar</button>
    </form>

    <?php if (!empty($erro)) : ?>
        <p style="color: red;"><?= esc($erro) ?></p>
    <?php endif; ?>

    <?php if (!empty($ficha)) : ?>
        <h2>Ficha de <?= esc($ficha['nome_paciente']) ?></h2>
        <p>Status: <strong><?= esc($ficha['status']) ?></strong></p>

        <?php if ($posicao): ?>
            <p>Sua posição atual na fila: <strong><?= $posicao ?></strong></p>
        <?php elseif (!empty($mensagem)): ?>
            <p><?= esc($mensagem) ?></p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
