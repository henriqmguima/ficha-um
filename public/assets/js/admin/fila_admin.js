// atualiza contadores "tempo-espera" baseados em data-inicio (timestamp em segundos)
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

setInterval(atualizarTempos, 1000);
atualizarTempos();

async function atualizarFilaAdmin() {
    try {
        const response = await fetch(API_LISTAR_FICHAS, { cache: 'no-store' });
        if (!response.ok) throw new Error('Erro ao buscar fichas');
        const fichas = await response.json();

        const tabela = document.getElementById('tabela-fichas');
        const tbody = tabela.querySelector('tbody') || tabela;
        // limpa tbody
        if (tbody.tagName.toLowerCase() === 'tbody') {
            tbody.innerHTML = '';
        } else {
            // se tabela foi passada diretamente
            tabela.innerHTML = '';
        }

        // monta cabeçalho (se não existir)
        const theadExists = tabela.querySelector('thead');
        if (!theadExists) {
            const thead = document.createElement('thead');
            thead.innerHTML = `
                <tr>
                    <th>ID</th>
                    <th>Paciente</th>
                    <th>Serviço</th>
                    <th>Status</th>
                    <th>Posição</th>
                    <th>Data</th>
                    <th>Tempo de Espera</th>
                    <th>Ações</th>
                </tr>
            `;
            tabela.prepend(thead);
        }

        if (!Array.isArray(fichas) || fichas.length === 0) {
            const tr = document.createElement('tr');
            tr.innerHTML = '<td colspan="8">Nenhuma ficha cadastrada.</td>';
            tabela.querySelector('tbody').appendChild(tr);
            return;
        }

        fichas.forEach(ficha => {
            const tr = document.createElement('tr');

            const tempoSpan = ficha.status === 'aguardando'
                ? `<span class="tempo-espera" data-inicio="${ficha.inicio_timestamp || ''}">${ficha.tempo_espera || '00:00:00'}</span>`
                : '—';

            const acoes = ficha.status === 'aguardando'
                ? `<a href="/admin/fichas/avaliar/${ficha.id}" title="Realizar Triagem"><i class="fa fa-notes-medical"></i></a>`
                : ficha.status === 'acolhido'
                    ? '<span class="badge bg-info">Em Triagem</span>'
                    : ficha.status === 'chamado'
                        ? '<span class="badge bg-warning">Chamado pelo médico</span>'
                        : '<span class="badge bg-success">Atendido</span>';

            const excluir = `<a href="/admin/fichas/delete/${ficha.id}" onclick="return confirm('Tem certeza que deseja excluir?')" title="Excluir"><i class="fa fa-trash"></i></a>`;

            tr.innerHTML = `
                <td>${ficha.id}</td>
                <td>${escapeHtml(ficha.nome_paciente)}</td>
                <td>${escapeHtml(ficha.tipo_atendimento || '—')}</td>
                <td>${escapeHtml(ficha.status)}</td>
                <td>${ficha.posicao ?? '—'}</td>
                <td>${escapeHtml(ficha.data_formatada)}</td>
                <td>${tempoSpan}</td>
                <td>${acoes} ${excluir}</td>
            `;

            tabela.querySelector('tbody').appendChild(tr);
        });

        // atualiza tempos imediatamente
        atualizarTempos();

    } catch (err) {
        console.error('Erro ao carregar fichas:', err);
    }
}

function escapeHtml(str) {
    if (str === undefined || str === null) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

atualizarFilaAdmin();
setInterval(atualizarFilaAdmin, 5000);
