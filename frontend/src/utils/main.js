export function alerta(xMensagem, sTipo = 'ok') {
   let aMensagens = (typeof xMensagem) == 'object' ? xMensagem : [xMensagem];

   aMensagens.forEach(sMensagem => {
      const oAlerta    = $('<div>').addClass('alerta').text(sMensagem);
      const oContainer = $('.alerta-container');

      if(sTipo == 'error') {
         oAlerta.css('background-color', '#ff5733');
      }

      oContainer.append(oAlerta);

      setTimeout(() => {
         oAlerta.css('transform', 'translateX(110%)');
      }, 5000);

      oAlerta.on('transitionend', () => {
         setTimeout(() => {
            oAlerta.remove();
         }, 100);
      });

      oAlerta.on('click', function () {
         $(this).css('transform', 'translateX(100%)');
      });
   });
}

export function adicionarLoading() {
   document.getElementById('loading_sistema').classList.add('active');
}

export function removerLoading() { 
   document.getElementById('loading_sistema').classList.remove('active');
}

export function formatarCPF(oElemento) {
   let sCpf = oElemento.value;

   sCpf = sCpf.replace(/\D/g, '');
   sCpf = sCpf.replace(/(\d{3})(\d)/, '$1.$2');
   sCpf = sCpf.replace(/(\d{3})(\d)/, '$1.$2');
   sCpf = sCpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

   return oElemento.value = sCpf;
}

export function  formatarTelefone(sTelefone) {
  sTelefone = sTelefone.replace(/\D/g, '');

   if(sTelefone.length <= 10) {
      sTelefone = sTelefone.replace(/^(\d{2})(\d{4})(\d{0,4})$/, '($1) $2-$3');
   }
   else {
      sTelefone = sTelefone.replace(/^(\d{2})(\d{5})(\d{0,4})$/, '($1) $2-$3');
   }

  return sTelefone.trim();
}