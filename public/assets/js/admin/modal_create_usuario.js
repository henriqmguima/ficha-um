console.log('Modal Create JS Loaded');

function abrirModal_Usuario() {
    document.getElementById('modalUsuario').classList.add('show');
}

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
        document.querySelectorAll('.erro-msg').forEach(el => el.remove());

        const formData = new FormData(form);

        try {
            const response = await fetch('/admin/usuarios/store', {
                method: 'POST',
                body: formData
            });

            const json = await response.json();
            console.log(json); // 👈 aqui você vê o objeto completo no console

            if (json.success) {
                campoSenha.innerText = json.senha;
                form.style.display = 'none';
                resposta.style.display = 'block';

                feedback.className = 'alert alert-success mt-3';
                feedback.innerHTML = 'Usuário cadastrado com sucesso! Anote a senha abaixo.';
            } else {
                if (json.errors) {
                    for (const campo in json.errors) {
                        // tenta pegar pelo id do campo
                        let input = document.getElementById(campo);

                        // se não encontrar, tenta pelo padrão "_usuario"
                        if (!input) {
                            input = document.getElementById(campo + '_usuario');
                        }

                        if (input) {
                            const small = document.createElement('small');
                            small.className = 'erro-msg text-danger';
                            small.innerText = json.errors[campo];
                            input.insertAdjacentElement('afterend', small);
                        }
                    }
                }


                feedback.className = 'feedbackForm';
                feedback.innerHTML = json.message || 'Por favor, corrija os erros no formulário.';
            }
        } catch (error) {
            feedback.className = 'feedbackForm';
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
