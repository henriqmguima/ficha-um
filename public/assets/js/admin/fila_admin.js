// function atualizarTempos() {
//     const agora = Math.floor(Date.now() / 1000); // em segundos
//     document.querySelectorAll('.tempo-espera').forEach(el => {
//         const inicio = parseInt(el.dataset.inicio);
//         const diff = agora - inicio;

//         const horas = String(Math.floor(diff / 3600)).padStart(2, '0');
//         const minutos = String(Math.floor((diff % 3600) / 60)).padStart(2, '0');
//         const segundos = String(diff % 60).padStart(2, '0');

//         el.textContent = `${horas}:${minutos}:${segundos}`;
//     });
// }

// setInterval(atualizarTempos, 100000);
// atualizarTempos();


// async function atualizarFilaAdmin() {
//     try {
//         const response = await fetch(API_LISTAR_FICHAS);
//         const fichas = await response.json(); // ✅ Corrigido aqui

//         const tbody = document.getElementById('tabela-fichas');
//         tbody.innerHTML = '';

//         if (!Array.isArray(fichas) || fichas.length === 0) {
//             tbody.innerHTML = '<tr><td colspan="8">Nenhuma ficha cadastrada.</td></tr>';
//             return;
//         }
//             const thead = document.createElement('thead');
//             thead.innerHTML = `
//                 <tr>
//                     <th>ID</th>
//                     <th>Paciente</th>
//                     <th>Serviço</th>
//                     <th>Status</th>
//                     <th>Posição</th>
//                     <th>Data</th>
//                     <th>Tempo de Espera</th>
//                     <th>Ações</th>
//                 </tr>
//             `;
//         fichas.forEach(ficha => {

//             const tr = document.createElement('tr');

//             tbody.appendChild(thead);
//             const tempoEspera = ficha.status === 'aguardando'
//                 ? `<span class="tempo-espera">${ficha.tempo_espera}</span>`
//                 : '—';

//             const acoes = ficha.status === 'aguardando'
//                 ? `<a href="/admin/fichas/status/${ficha.id}/em_atendimento" title="Atender">
//                         <i class="fa fa-stethoscope"></i>
//                     </a>`
//                 : ficha.status === 'em_atendimento'
//                     ? `<a href="/admin/fichas/status/${ficha.id}/atendido" title="Finalizar">
//                             <i class="fa fa-check"></i>
//                         </a>`
//                     : '✔';

//             const excluir = `<a href="/admin/fichas/delete/${ficha.id}" onclick="return confirm('Tem certeza que deseja excluir?')" title="Excluir">
//                                 <i class="fa fa-trash"></i>
//                             </a>`;

//             tr.innerHTML = `
//                 <td>${ficha.id}</td>
//                 <td>${ficha.nome_paciente}</td>
//                 <td>${ficha.tipo_atendimento || '—'}</td>
//                 <td>${ficha.status}</td>
//                 <td>${ficha.posicao}</td>
//                 <td>${ficha.data_formatada}</td>
//                 <td>${tempoEspera}</td>
//                 <td>${acoes} ${excluir}</td>
//             `;

//             tbody.appendChild(tr);
//         });

//     } catch (err) {
//         console.error('Erro ao carregar fichas:', err);
//     }
// }


// // Inicial e intervalo
// atualizarFilaAdmin();
// setInterval(atualizarFilaAdmin, 5000);
