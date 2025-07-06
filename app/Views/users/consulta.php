<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="<?= base_url('assets/images/logo/favicon.ico') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/users/consulta.css') ?>">
    <title>Sua Ficha</title>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const conteudo = document.getElementById('conteudo');

            async function carregarFicha() {
                const res = await fetch('/api/fichas/minha-ficha');
                const data = await res.json();

                if (data.error) {
                    conteudo.innerHTML = `
                        <p>${data.messages?.error || 'Você ainda não possui uma ficha.'}</p>
                        <button id="btnCriarFicha">Solicitar Ficha</button>
                    `;

                    document.getElementById('btnCriarFicha')?.addEventListener('click', async () => {
                        const nome = "<?= esc(session('usuarioLogado')['nome']) ?>";
                        const cpf = "<?= esc(session('usuarioLogado')['cpf'] ?? '') ?>";

                        const resposta = await fetch('/api/fichas', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                nome_paciente: nome,
                                cpf: cpf
                            })
                        });

                        const resultado = await resposta.json();

                        if (resposta.ok) {
                            carregarFicha(); // recarrega após criar
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
