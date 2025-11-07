document.addEventListener("DOMContentLoaded", () => {
    const conteudo = document.getElementById("conteudo");

    async function carregarFicha() {
        try {
            const res = await fetch(`/api/fichas/minha-ficha`, { cache: "no-store" });
            const data = await res.json();

            // Caso não haja ficha ativa
            if (!res.ok || data.error || data.messages?.error) {
                conteudo.innerHTML = `
                    <div class="ficha-card">
                        <h2>Olá, ${usuarioLogado.nome}</h2>
                        <p>Posto de atendimento: <strong>${usuarioLogado.postoNome}</strong></p>
                        <p>Você ainda não possui uma ficha ativa.</p>
                        <button id="btnCriarFicha" class="btn-ficha">Solicitar Ficha</button>
                    </div>
                `;

                document.getElementById("btnCriarFicha")?.addEventListener("click", async () => {
                    try {
                        const resp = await fetch("/api/fichas", {
                            method: "POST",
                            headers: { "Content-Type": "application/json" },
                            body: JSON.stringify({
                                nome_paciente: usuarioLogado.nome,
                                cpf: usuarioLogado.cpf,
                                tipo_atendimento: "Clínico Geral"
                            })
                        });

                        if (resp.ok) {
                            carregarFicha();
                        } else {
                            const erro = await resp.json();
                            exibirMensagem("❌ " + (erro.message || "Erro ao solicitar ficha."), "erro");
                        }
                    } catch (e) {
                        exibirMensagem("❌ Erro de conexão ao criar ficha.", "erro");
                    }
                });
                return;
            }

            // Ficha encontrada
            const nome = data.nome_paciente || "—";
            const status = data.status || "—";
            const posicao = data.posicao_na_fila ?? "—";
            const tempo = data.tempo_espera || "—";
            const posto = data.posto_nome || usuarioLogado.posto || "Não informado";

            let classeStatus = "";
            let mensagemStatus = "";

            switch (status) {
                case "aguardando":
                    classeStatus = "aguardando";
                    mensagemStatus = "⏳ Aguardando atendimento...";
                    break;
                case "acolhido":
                case "chamado":
                    classeStatus = "em-atendimento";
                    mensagemStatus = "⚕️ Em atendimento médico.";
                    break;
                case "atendido":
                    classeStatus = "finalizado";
                    mensagemStatus = "✅ Atendimento finalizado.";
                    break;
                default:
                    classeStatus = "aguardando";
                    mensagemStatus = "ℹ️ Aguardando atualização...";
            }

            conteudo.innerHTML = `
                <div class="ficha-card ${classeStatus}">
                    <h2>Ficha de ${nome}</h2>
                    <p><strong>Posto:</strong> ${posto}</p>
                    <p><strong>Status:</strong> <span class="${classeStatus}">${status}</span></p>
                    ${status === "aguardando"
                    ? `
                            <p><strong>Posição na fila:</strong> ${posicao}</p>
                            <p><strong>Tempo de espera:</strong> ${tempo}</p>
                            `
                    : status === "acolhido" || status === "chamado"
                        ? `<p><strong>Tempo desde a chegada:</strong> ${tempo}</p>`
                        : ""
                }
                    <p class="${classeStatus}">${mensagemStatus}</p>
                </div>
            `;

        } catch (error) {
            console.error("Erro ao carregar ficha:", error);
            conteudo.innerHTML = `
                <div class="ficha-card">
                    <p>⚠️ Erro ao carregar ficha. Verifique sua conexão e tente novamente.</p>
                </div>
            `;
        }
    }

    function exibirMensagem(texto, tipo = "info") {
        const msg = document.createElement("div");
        msg.className = `toast ${tipo}`;
        msg.textContent = texto;
        document.body.appendChild(msg);
        setTimeout(() => msg.remove(), 3000);
    }

    carregarFicha();
    setInterval(carregarFicha, 5000);
});
