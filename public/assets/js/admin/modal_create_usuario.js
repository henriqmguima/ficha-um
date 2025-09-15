console.log('Modal Create JS Loaded');

function abrirModal_Usuario() {
    document.getElementById('modalUsuario').classList.add('show');
}
formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formUsuario');
    const resposta = document.getElementById('respostaSenha');
    const campoSenha = document.getElementById('senhaGerada');
    const feedback = document.getElementById('feedbackForm'); // container p/ mensagens

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        // limpar mensagens anteriores
        feedback.innerHTML = '';
        feedback.className = '';

        const formData = new FormData(form);

        try {
            const response = await fetch('/admin/usuarios/store', {
                method: 'POST',
                body: formData
            });

            const json = await response.json();

            if (json.success) {
                campoSenha.innerText = json.senha;
                form.style.display = 'none';
                resposta.style.display = 'block';

                feedback.className = 'alert alert-success mt-3';
                feedback.innerHTML = 'Usuário cadastrado com sucesso! Anote a senha abaixo.';
            } else {
                feedback.className = 'alert alert-danger mt-3';
                feedback.innerHTML = json.message || 'Erro ao cadastrar.';
            }
        } catch (error) {
            feedback.className = 'alert alert-danger mt-3';
            feedback.innerHTML = 'Erro de comunicação com o servidor. Tente novamente.';
        }
    });
});

function fecharModal_Usuario() {
    const form = document.getElementById('formUsuario');
    const resposta = document.getElementById('respostaSenha');
    const feedback = document.getElementById('feedbackForm');

    // Resetar campos
    form.reset();
    form.style.display = 'block';
    resposta.style.display = 'none';
    feedback.innerHTML = '';
    feedback.className = '';

    document.getElementById('modalUsuario').classList.remove('show');
}
