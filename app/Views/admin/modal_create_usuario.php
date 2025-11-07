<div class="modal-overlay" id="modalUsuario">
    <div class="modal">
        <span class="close" onclick="fecharModal_Usuario()">&times;</span>
        <h2>Cadastrar Novo Usuário</h2>

        <!-- FORMULÁRIO -->
        <form id="formUsuario" method="post">
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

            <div class="form-group">
                <label for="role">Função:</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="usuario">Usuário (paciente)</option>
                    <option value="medico">Médico</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>

            <button type="submit" class="btn">Cadastrar</button>
        </form>

        <!-- RESPOSTA COM A SENHA -->
        <div id="respostaSenha" class="success-message" style="display:none;">
            ✅ Usuário cadastrado!<br>
            Senha gerada: <strong id="senhaGerada"></strong>
        </div>
    </div>
</div>