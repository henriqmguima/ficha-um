document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('fichas-disponiveis');

    // segurança: se a view não tiver o container, não faz nada
    if (!container) return;

    async function atualizarFila() {
        try {
            const API_FICHAS = document.body.dataset.apiFichas;
            const res = await fetch(API_FICHAS, { cache: 'no-store' });
            if (!res.ok) throw new Error('Resposta inválida da API');

            const json = await res.json();
            const fichas = Array.isArray(json.fichas) ? json.fichas : [];

            // limpa
            container.innerHTML = '';

            if (fichas.length === 0) {
                container.innerHTML = '<p>Nenhuma ficha disponível no momento.</p>';
                return;
            }

            fichas.forEach(ficha => {
                const col = document.createElement('div');
                col.className = 'col-md-4 mb-3';

                const cores = {
                    'vermelho': 'bg-danger text-white',
                    'laranja': 'bg-warning text-dark',
                    'amarelo': 'bg-warning-subtle text-dark',
                    'verde': 'bg-success text-white',
                    'azul': 'bg-primary text-white'
                };
                const classe = cores[ficha.prioridade_manchester] || 'bg-light';

                col.innerHTML = `
                    <div class="card ${classe} shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">${escapeHtml(ficha.nome_paciente)}</h5>
                            <p><strong>Sintomas:</strong> ${escapeHtml(ficha.sintomas || '—')}</p>
                            <a href="/medico/assumir/${ficha.id}" class="btn btn-light btn-sm">👩‍⚕️ Assumir</a>
                            <a href="/medico/ver/${ficha.id}" class="btn btn-outline-light btn-sm">Detalhes</a>
                        </div>
                    </div>
                `;
                container.appendChild(col);
            });

        } catch (err) {
            console.error('Erro ao atualizar fila:', err);
        }
    }

    // escape simples para evitar XSS
    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // polling
    atualizarFila();
    setInterval(atualizarFila, 4000); // 4s - ajustável
});
