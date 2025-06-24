<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sua Ficha</title>
    <script>
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

            // Atualiza a cada 10 segundos
            carregarFicha();
            setInterval(carregarFicha, 10000);
        });
    </script>
</head>
<body>
    <h1>Olá, <?= esc(session('usuarioLogado')['nome']) ?>!</h1>

    <div id="conteudo">
        <p>Carregando informações da sua ficha...</p>
    </div>

    <p><a href="<?= site_url('logout') ?>">Sair</a></p>
</body>
</html>
