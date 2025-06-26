console.log('Modal Create JS Loaded');
function abrirModal_Usuario() {
    document.getElementById('modalUsuario').classList.add('show');
}

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formUsuario');
    const resposta = document.getElementById('respostaSenha');
    const campoSenha = document.getElementById('senhaGerada');

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

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
            } else {
                alert(json.message || 'Erro ao cadastrar.');
            }
        } catch (error) {
            alert('Erro na requisição');
        }
    });
});

function fecharModal_Usuario() {
    const form = document.getElementById('formUsuario');
    const resposta = document.getElementById('respostaSenha');

    // Resetar campos
    form.reset();
    form.style.display = 'block';
    resposta.style.display = 'none';

    document.getElementById('modalUsuario').classList.remove('show');
}
