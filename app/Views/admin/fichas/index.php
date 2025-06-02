<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Fila de Atendimento</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h1>Fila de Atendimento - Administração</h1>
    <p>
        <a href="<?= site_url('admin/fichas/create') ?>">+ Nova Ficha</a>
    </p>

    <?php if (!empty($fichas)) : ?>
        <table>
<thead>
    <tr>
        <th>ID</th>
        <th>Nome do Paciente</th>
        <th>Tipo de Atendimento</th>
        <th>Status</th>
        <th>Data de Criação</th>
        <th>Ações</th> <!-- NOVO -->
    </tr>
    </thead>
    <tbody>
        <?php foreach ($fichas as $ficha) : ?>
            <tr>
                <td><?= esc($ficha['id']) ?></td>
                <td><?= esc($ficha['nome_paciente']) ?></td>
                <td><?= esc($ficha['tipo_atendimento']) ?></td>
                <td><?= esc($ficha['status']) ?></td>
                <td><?= esc($ficha['criado_em']) ?></td>
                <td>
                    <?php if ($ficha['status'] == 'aguardando'): ?>
                        <a href="<?= site_url('admin/fichas/status/' . $ficha['id'] . '/em_atendimento') ?>">Atender</a>
                    <?php elseif ($ficha['status'] == 'em_atendimento'): ?>
                        <a href="<?= site_url('admin/fichas/status/' . $ficha['id'] . '/atendido') ?>">Finalizar</a>
                    <?php else: ?>
                        <span>✔ Atendido</span>
                    <?php endif; ?>
                    | 
                    <a href="<?= site_url('admin/fichas/delete/' . $ficha['id']) ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
        </table>
    <?php else : ?>
        <p>Nenhuma ficha cadastrada.</p>
    <?php endif; ?>
</body>
</html>
