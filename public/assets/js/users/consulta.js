document.addEventListener('DOMContentLoaded', function () {
    const cpf = "<?= session('usuarioLogado')['cpf'] ?>";

    async function carregarFicha() {
        try {
            const response = await fetch(`/api/fichas/minha-ficha?cpf=${cpf}`);
            const data = await response.json();

            if (data.error) {
                document.getElementById('conteudo').innerHTML = `<p>${data.messages.error}</p>`;
                return;
            }

            let html = `
                <h2>Ficha de ${data.nome_paciente}</h2>
                <p><strong>Status:</strong> ${data.status}</p>
            `;

            if (data.status === 'aguardando') {
                html += `<p><strong>Sua posição na fila:</strong> ${data.posicao_na_fila}</p>`;
            } else if (data.status === 'em_atendimento') {
                html += `<p>⚕️ Você está em atendimento.</p>`;
            } else {
                html += `<p>✅ Seu atendimento foi concluído.</p>`;
            }

            document.getElementById('conteudo').innerHTML = html;

        } catch (e) {
            document.getElementById('conteudo').innerHTML = `<p>Erro ao buscar os dados.</p>`;
        }
    }

    // Atualiza a cada 5 segundos
    carregarFicha();
    setInterval(carregarFicha, 5000);
});

body: JSON.stringify({
    nome_paciente: nome,
    cpf: cpf,
    tipo_atendimento: "Clínico Geral"
})