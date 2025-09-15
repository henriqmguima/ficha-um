<div class="modal-overlay" id="modalUsuario">
    <div class="modal">

        <form id="formUsuario" action="<?= site_url('admin/usuarios/store') ?>" method="post" novalidate>
            <?= csrf_field() ?>
            <div class="form-sections">
                <div class="form-box">
                    <span class="close" onclick="fecharModal_Usuario()">&times;</span>
                    <h2>Cadastrar Novo Usuário</h2>

                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?= old('nome') ?>" required>
                    <?php if (isset($validation) && $validation->hasError('nome')): ?>
                        <small class="erro-msg"><?= $validation->getError('nome') ?></small>
                    <?php endif; ?>

                    <label for="cpf">CPF:</label>
                    <input type="text" id="cpf" name="cpf" value="<?= old('cpf') ?>" required>
                    <?php if (isset($validation) && $validation->hasError('cpf')): ?>
                        <small class="erro-msg"><?= $validation->getError('cpf') ?></small>
                    <?php endif; ?>

                    <label for="cartao_sus">Cartão SUS:</label>
                    <input type="text" id="cartao_sus" name="cartao_sus" value="<?= old('cartao_sus') ?>">
                    <?php if (isset($validation) && $validation->hasError('cartao_sus')): ?>
                        <small class="erro-msg"><?= $validation->getError('cartao_sus') ?></small>
                    <?php endif; ?>

                    <label for="endereco">Endereço:</label>
                    <input type="text" id="endereco" name="endereco" value="<?= old('endereco') ?>">
                    <?php if (isset($validation) && $validation->hasError('endereco')): ?>
                        <small class="erro-msg"><?= $validation->getError('endereco') ?></small>
                    <?php endif; ?>

                    <label for="telefone">Telefone:</label>
                    <input type="text" id="telefone" name="telefone" value="<?= old('telefone') ?>">
                    <?php if (isset($validation) && $validation->hasError('telefone')): ?>
                        <small class="erro-msg"><?= $validation->getError('telefone') ?></small>
                    <?php endif; ?>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= old('email') ?>">
                    <?php if (isset($validation) && $validation->hasError('email')): ?>
                        <small class="erro-msg"><?= $validation->getError('email') ?></small>
                    <?php endif; ?>
                    <button type="submit" class="btn">Cadastrar</button>
                </div>
            </div>
        </form>

        <div id="respostaSenha" class="success-message" style="display:none;">
            ✅ Usuário cadastrado!<br>
            Senha gerada: <strong id="senhaGerada"></strong>
        </div>
        <div id="feedbackForm"></div>

    </div>
</div>