<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nova Ficha</title>
</head>
<body>
    <h1>Criar Nova Ficha</h1>

    <form action="<?= site_url('admin/fichas/store') ?>" method="post">
        <label for="nome_paciente">Nome do Paciente:</label><br>
        <input type="text" name="nome_paciente" id="nome_paciente" required><br><br>

        <label for="tipo_atendimento">Tipo de Atendimento:</label><br>
        <input type="text" name="tipo_atendimento" id="tipo_atendimento"><br><br>

        <button type="submit">Salvar</button>
    </form>

    <p><a href="<?= site_url('admin/fichas') ?>">Voltar</a></p>
</body>
</html>
