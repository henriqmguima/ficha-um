<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuário | Ficha Um</title>
</head>
<body>
    <h2>Cadastro de Novo Usuário</h2>

    <form method="post" action="<?= site_url('admin/usuarios/store') ?>">
        <label>Nome:</label><br>
        <input type="text" name="nome" required><br>

        <label>CPF:</label><br>
        <input type="text" name="cpf" required><br>

        <label>Cartão SUS:</label><br>
        <input type="text" name="cartao_sus"><br>

        <label>Endereço:</label><br>
        <input type="text" name="endereco"><br>

        <label>Email:</label><br>
        <input type="email" name="email"><br>

        <label>É administrador?</label><br>
        <input type="checkbox" name="is_admin" value="1"><br><br>

        <button type="submit">Cadastrar</button>
    </form>
    <?php if (session()->getFlashdata('senhaGerada')) : ?>
        <p style="color: green;">
            ✅ Senha gerada para o usuário: <strong><?= session()->getFlashdata('senhaGerada') ?></strong>
        </p>
    <?php endif; ?>

</body>
</html>