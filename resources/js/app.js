import './bootstrap';


import Swal from 'sweetalert2';
import axios from 'axios';
import { library, dom } from '@fortawesome/fontawesome-svg-core';
import { faShoppingCart } from '@fortawesome/free-solid-svg-icons';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.Swal = Swal;



// Adicionar o ícone à biblioteca
library.add(faShoppingCart);

// Comando crucial: substitui as tags <i> por SVGs automaticamente
dom.watch();




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