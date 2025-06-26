function atualizarTempos() {
    const agora = Math.floor(Date.now() / 1000); // em segundos
    document.querySelectorAll('.tempo-espera').forEach(el => {
        const inicio = parseInt(el.dataset.inicio);
        const diff = agora - inicio;

        const horas = String(Math.floor(diff / 3600)).padStart(2, '0');
        const minutos = String(Math.floor((diff % 3600) / 60)).padStart(2, '0');
        const segundos = String(diff % 60).padStart(2, '0');

        el.textContent = `${horas}:${minutos}:${segundos}`;
    });
}

setInterval(atualizarTempos, 100000);
atualizarTempos(); 
