<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Ficha Um</title>
</head>
<body>
    <h2>Login</h2>

    <?php if (session()->getFlashdata('erro')) : ?>
        <p style="color: red;"><?= session()->getFlashdata('erro') ?></p>
    <?php endif; ?>

    <form method="post" action="<?= site_url('login/autenticar') ?>">
        <label>CPF:</label><br>
        <input type="text" name="cpf" required><br>

        <label>Senha:</label><br>
        <input type="password" name="senha" required><br><br>

        <button type="submit">Entrar</button>
    </form>

</body>
</html>