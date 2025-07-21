document.addEventListener('DOMContentLoaded', function () {
    const conteudo = document.getElementById('conteudo');

    async function carregarFicha() {
        const res = await fetch(`/ficha-um/api/fichas/minha-ficha`);
        const data = await res.json();

        if (data.error) {
            conteudo.innerHTML = `
                <p>${data.messages?.error || 'Você ainda não possui uma ficha.'}</p>
                <button id="btnCriarFicha">Solicitar Ficha</button>
            `;

            document.getElementById('btnCriarFicha')?.addEventListener('click', async () => {
                const resposta = await fetch(`/ficha-um/api/fichas`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        nome_paciente: usuarioLogado.nome,
                        cpf: usuarioLogado.cpf,
                        tipo_atendimento: "Clínico Geral"
                    })
                });

                const resultado = await resposta.json();

                if (resposta.ok) {
                    carregarFicha();
                } else {
                    alert(resultado.messages?.error || 'Erro ao solicitar ficha.');
                }
            });

            return;
        }

        let html = `
            <h2>Ficha de ${data.nome_paciente}</h2>
            <p><strong>Status:</strong> ${data.status}</p>
        `;

        if (data.status === 'aguardando') {
            html += `<p><strong>Posição na fila:</strong> ${data.posicao_na_fila}</p>`;
        } else if (data.status === 'em_atendimento') {
            html += `<p>⚕️ Você está em atendimento.</p>`;
        } else {
            html += `<p>✅ Atendimento finalizado.</p>`;
        }

        conteudo.innerHTML = html;
    }

    carregarFicha();
    setInterval(carregarFicha, 5000);
});
