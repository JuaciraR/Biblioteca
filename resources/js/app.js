import './bootstrap';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Swal = Swal;

window.Alpine = Alpine;

Alpine.start();

// Importação do Axios (necessário para o Laravel/Jetstream)
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


// --- LÓGICA FINAL PARA DISPARO DO SWEETALERT ---
// Adiciona um listener global no nível da janela para ouvir eventos Livewire
window.addEventListener('show-alert', event => {
    // Livewire dispara o evento com o payload dentro de event.detail[0]
    const data = event.detail[0] || event.detail; 

    // O SweetAlert é disparado com base nos dados do PHP
    Swal.fire({
        icon: data.type, // 'success' ou 'error'
        title: data.title || (data.type === 'success' ? 'Sucesso!' : 'Erro de Validação!'),
        text: data.message,
        toast: data.toast || false,
        position: data.position || 'top-end', // Posição no canto superior direito
        showConfirmButton: data.showConfirmButton || false, // Não mostra o botão, apenas esconde sozinho
        timer: data.timer || 4000, // Fica visível por 4 segundos
        timerProgressBar: true,
    });
});