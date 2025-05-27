import { format } from 'date-fns';

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

export function formatarCPF(xValor) {
   let sCpf = xValor;

   if(typeof xValor == 'object') {
      sCpf = xValor.value;
   }

   sCpf = sCpf.replace(/\D/g, '');
   sCpf = sCpf.replace(/(\d{3})(\d)/, '$1.$2');
   sCpf = sCpf.replace(/(\d{3})(\d)/, '$1.$2');
   sCpf = sCpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

   if(typeof xValor == 'object') {
      return xValor.value = sCpf;
   }

   return sCpf;
}

export function formatarTelefone(sValor) {
    if(sValor.length < 8) {
        utils.alerta('O valor informado é inválido', 'error');
        return '';
    }

    if(sValor.length < 11) {
        if(sValor.length == 10) {
            return sValor.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
        }

        if(sValor.length == 8) {
            return sValor.replace(/(\d{4})(\d{4})/, '(47) $1-$2');
        }

        return sValor.replace(/(\d{5})(\d{4})/, '(47) $1-$2');
    }

    return sValor.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
}

export function formatarDataBR(sData) {
   if(!sData) {
      return '';
   }

   const aPartes = sData.split('-');

   if(aPartes.length !== 3) {
      return '';
   }

   const sAno = aPartes[0];
   const sMes = aPartes[1];
   const sDia = aPartes[2];

   return `${sDia}/${sMes}/${sAno}`;
}

export function validarCamposObrigatorios() {
   let bCamposPreenchidos = true;

   const oBotao         = document.activeElement;
   const oCardPrincipal = oBotao?.closest('.card-principal');

   if(!oCardPrincipal) {
      return false;
   }

   const aLabels = oCardPrincipal.querySelectorAll('.obrigatorio');

   for(const oLabel of aLabels) {
      const oCampo = oLabel.nextElementSibling;

      if(!oCampo) {
         continue;
      }

      const sTipoCampo = oCampo.tagName.toLowerCase();

      if(['input', 'select', 'textarea'].includes(sTipoCampo) && !oCampo.value.trim()) {
         bCamposPreenchidos = false;

         utils.alerta(`O campo "${oLabel.innerText}" é obrigatório.`, 'error');
      }
   }

   return bCamposPreenchidos;
}

export function formatarData(sValor) {
    if(sValor.length < 10) {
        utils.alerta('Data inválida', 'error');
        return '';
    }
    
    return format(sValor, 'dd/MM/yyyy');
}

export function formatarCPFCNPJ(sValor) {
    sValor = sValor.replace(/\D/g, '');

    if(sValor.length < 11 || sValor.length > 14) {
        utils.alerta('O valor informado é inválido', 'error');
        return '';
    }
    if(sValor.length > 11) {
        return sValor.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
    }

    return sValor.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
}

export function converterParaMoeda(valor) {
   const iValor = parseFloat(valor?.toString().replace(',', '.'));

   if(isNaN(iValor)) {
      return valor;
   }

   const valorFormatado = iValor.toFixed(2).replace('.', ',');
   return 'R$ ' + valorFormatado;
}
