// Atualiza o contador de tempo (roda a cada 1s)
function atualizarTempos() {
    const agora = Math.floor(Date.now() / 1000);
    document.querySelectorAll('.tempo-espera').forEach(el => {
        const inicio = parseInt(el.dataset.inicio, 10);
        if (!inicio) return;
        const diff = Math.max(0, agora - inicio);
        const horas = String(Math.floor(diff / 3600)).padStart(2, '0');
        const minutos = String(Math.floor((diff % 3600) / 60)).padStart(2, '0');
        const segundos = String(diff % 60).padStart(2, '0');
        el.textContent = `${horas}:${minutos}:${segundos}`;
    });
}

// Atualiza a tabela de fichas (roda a cada 5s)
async function atualizarFilaAdmin() {
    try {
        const response = await fetch(API_LISTAR_FICHAS, { cache: 'no-store' });
        if (!response.ok) throw new Error('Erro ao buscar fichas');
        const fichas = await response.json();

        const tabela = document.getElementById('tabela-fichas');
        const tbody = tabela.querySelector('tbody');
        tbody.innerHTML = ''; // limpa tabela

        if (!Array.isArray(fichas) || fichas.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8">Nenhuma ficha cadastrada.</td></tr>';
            return;
        }

        fichas.forEach(ficha => {
            const tr = document.createElement('tr');

            // Timestamp de criação (para contagem ao vivo)
            const criadoEm = new Date(ficha.criado_em).getTime() / 1000;

            const tempoSpan = ficha.status === 'aguardando'
                ? `<span class="tempo-espera" data-inicio="${criadoEm}">${ficha.tempo_espera || '00:00:00'}</span>`
                : '—';

            // 🔹 Status com estilo visual
            const statusSpan = `
                <span class="status-badge status-${ficha.status}">
                    ${ficha.status.charAt(0).toUpperCase() + ficha.status.slice(1)}
                </span>
            `;

            // 🔹 Ícones de ação com cor
            const acoes = ficha.status === 'aguardando'
                ? `<a href="/admin/fichas/avaliar/${ficha.id}" title="Realizar Triagem">
                      <i class="fa fa-notes-medical" style="color:#2563eb;"></i>
                   </a>`
                : ficha.status === 'acolhido'
                    ? '<span class="badge bg-info">Em Triagem</span>'
                    : ficha.status === 'chamado'
                        ? '<span class="badge bg-warning">Chamado pelo médico</span>'
                        : '<span class="badge bg-success">Atendido</span>';

            // 🔹 Botão de exclusão com modal moderno
            const excluir = `
                <a href="javascript:void(0);" onclick="abrirModalExclusao(${ficha.id})" title="Excluir">
                    <i class="fa fa-trash" style="color:#b91c1c;"></i>
                </a>`;

            tr.innerHTML = `
                <td>${ficha.id}</td>
                <td>${escapeHtml(ficha.nome_paciente)}</td>
                <td>${escapeHtml(ficha.tipo_atendimento || '—')}</td>
                <td>${statusSpan}</td>
                <td>${ficha.posicao ?? '—'}</td>
                <td>${escapeHtml(ficha.data_formatada)}</td>
                <td>${tempoSpan}</td>
                <td>${acoes} ${excluir}</td>
            `;

            tbody.appendChild(tr);
        });

    } catch (err) {
        console.error('Erro ao carregar fichas:', err);
    }
}

// Escapar texto (segurança)
function escapeHtml(str) {
    if (str === undefined || str === null) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

// ✅ Garante que o modal funcione mesmo em elementos criados dinamicamente
window.abrirModalExclusao = function (idFicha) {
    idFichaSelecionada = idFicha;
    document.getElementById("modalConfirmacao").style.display = "flex";
};

// Inicialização
(async function init() {
    await atualizarFilaAdmin();     // carrega imediatamente
    setInterval(atualizarFilaAdmin, 5000); // atualiza fichas a cada 5s
    setInterval(atualizarTempos, 1000);    // atualiza contadores em tempo real
})();
