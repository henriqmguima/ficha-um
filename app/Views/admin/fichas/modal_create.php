<!-- MODAL: Nova Ficha -->
<div class="modal-overlay" id="modalFicha">
    <div class="modal">
        <button class="modal-close" onclick="fecharModal()">&times;</button>
        <h2>Nova Ficha de Atendimento</h2>

        <form action="<?= site_url('admin/fichas/store') ?>" method="post">
            <label for="cpf">Selecione o paciente:</label>
            <select name="cpf" id="cpf" required>
                <option value="">-- Selecione --</option>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= esc($usuario['cpf']) ?>">
                        <?= esc($usuario['nome']) ?> - CPF: <?= esc($usuario['cpf']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="tipo_atendimento">Tipo de Atendimento:</label>
            <input type="text" name="tipo_atendimento" id="tipo_atendimento" required>

            <button type="submit">Criar Ficha</button>
        </form>
    </div>
</div>
