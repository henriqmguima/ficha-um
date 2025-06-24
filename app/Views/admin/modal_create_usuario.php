<div class="modal-overlay" id="modalUsuario">
    <div class="modal">

        <span class="close" onclick="fecharModal_Usuario()">&times;</span>
        <h2>Cadastrar Novo Usuário</h2>

        <form method="post" action="<?= site_url('admin/usuarios/store') ?>">
            <label>Nome:</label>
            <input type="text" name="nome" required>

            <label>CPF:</label>
            <input type="text" name="cpf" required>

            <label>Cartão SUS:</label>
            <input type="text" name="cartao_sus">

            <label>Endereço:</label>
            <input type="text" name="endereco">

            <label>Email:</label>
            <input type="email" name="email">

            <label class="checkbox-label">
                <input type="checkbox" name="is_admin" value="1"> É administrador?
            </label>

            <button type="submit" class="btn">Cadastrar</button>
        </form>

        <?php if (session()->getFlashdata('senhaGerada')) : ?>
            <p class="success-message">
                ✅ Senha gerada: <strong><?= session()->getFlashdata('senhaGerada') ?></strong>
            </p>
        <?php endif; ?>
    </div>
</div>
