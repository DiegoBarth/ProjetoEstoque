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