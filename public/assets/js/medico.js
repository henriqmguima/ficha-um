document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("fichas-disponiveis");
    if (!container) return;

    const body = document.body;
    const API_FICHAS = body.dataset.apiFichas;
    const ASSUMIR_BASE = body.dataset.assumirBase;
    const VER_BASE = body.dataset.verBase;

    // 🔹 Renderiza fichas vindas da API
    async function atualizarFila() {
        try {
            const res = await fetch(API_FICHAS, { cache: "no-store" });
            if (!res.ok) throw new Error("Resposta inválida da API");

            const json = await res.json();
            const fichas = Array.isArray(json.fichas) ? json.fichas : [];

            container.innerHTML = "";

            if (fichas.length === 0) {
                container.innerHTML = "<p>Nenhuma ficha disponível no momento.</p>";
                return;
            }

            fichas.forEach((ficha) => {
                const col = document.createElement("div");
                col.className = "col-md-4 mb-3";

                const cores = {
                    vermelho: "bg-danger text-white",
                    laranja: "bg-warning text-dark",
                    amarelo: "bg-warning-subtle text-dark",
                    verde: "bg-success text-white",
                    azul: "bg-primary text-white",
                };
                const classe = cores[ficha.prioridade_manchester] || "bg-light";
                const sintomas = ficha.sintomas ? escapeHtml(ficha.sintomas) : "—";
                const nome = escapeHtml(ficha.nome_paciente || "—");
                const tempo = escapeHtml(ficha.tempo_espera || "—");

                col.innerHTML = `
                    <div class="card ${classe} shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">${nome}</h5>
                            <p><strong>Sintomas:</strong> ${sintomas}</p>
                            <p class="tempo-espera" data-criado="${ficha.criado_em}">
                                <strong>⏱ Tempo de espera:</strong> <span>${tempo}</span>
                            </p>
                            <a href="${ASSUMIR_BASE}/${ficha.id}" class="btn btn-light btn-sm">👩‍⚕️ Assumir</a>
                            <a href="${VER_BASE}/${ficha.id}" class="btn btn-outline-light btn-sm">Detalhes</a>
                        </div>
                    </div>
                `;
                container.appendChild(col);
            });
        } catch (err) {
            console.error("Erro ao atualizar fila:", err);
        }
    }

    // 🔹 Atualiza o tempo exibido a cada 60 s
    function atualizarTempos() {
        const agora = new Date();
        document.querySelectorAll(".tempo-espera").forEach((el) => {
            const criadoEm = new Date(el.dataset.criado);
            const diffMs = agora - criadoEm;
            const diffMin = Math.floor(diffMs / 60000);
            const horas = Math.floor(diffMin / 60);
            const minutos = diffMin % 60;
            const texto = horas > 0 ? `${horas}h ${minutos}min` : `${minutos} min`;
            el.querySelector("span").textContent = texto;
        });
    }

    function escapeHtml(str) {
        if (str === undefined || str === null) return "";
        return String(str)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    atualizarFila();               // carrega inicialmente
    setInterval(atualizarFila, 30000); // atualiza a lista a cada 30 s
    setInterval(atualizarTempos, 60000); // atualiza só os tempos a cada 60 s
});
