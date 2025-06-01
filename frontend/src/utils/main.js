import { format, parseISO } from 'date-fns';

export function limparCampos() {
   const aCampos = $('#modalCadastro').find('input, textarea, select');

   for(const oCampo of aCampos) {        
      oCampo.value = '';
   }
}

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

export function formatarCPFCNPJ(xValor) {
   let sCnpj = xValor;

   if(typeof xValor == 'object') {
      sCnpj = xValor.value;
   }

   sCnpj = sCnpj.replace(/\D/g, '');
   sCnpj = sCnpj.replace(/(\d{2})(\d)/, '$1.$2');
   sCnpj = sCnpj.replace(/(\d{3})(\d)/, '$1.$2');
   sCnpj = sCnpj.replace(/(\d{3})(\d)/, '$1/$2');
   sCnpj = sCnpj.replace(/(\d{4})(\d{1,2})/, '$1-$2');

   if(typeof xValor == 'object') {
      return xValor.value = sCnpj;
   }

   return sCnpj;
}

export function formatarTelefone(xValor) {
   let sTelefone = xValor;

   if(typeof xValor == 'object') {
      sTelefone = xValor.value;
   }

   sTelefone = sTelefone.replace(/\D/g, '');

   sTelefone = sTelefone.replace(/(\d{2})(\d)/,  '($1) $2');    
   sTelefone = sTelefone.replace(/(\d{5})(\d{1,4})$/, '$1-$2');

   if(typeof xValor == 'object') {
      xValor.value = sTelefone;
   }

   return sTelefone;    
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
   if(!sValor) {
      return null;
   }
   
   if(sValor.length < 10) {
      utils.alerta('Data inválida', 'error');
      return '';
   }
   
   return format(parseISO(sValor), 'dd/MM/yyyy');
}

export function formatarDataHora(dataHoraStr) {
  const [sData, sHora] = dataHoraStr.split(' ');
  
  const [sAno, sMes, sDia] = sData.split('-');
  
  return `${sDia}/${sMes}/${sAno} ${sHora}`;
}

export function converterParaMoeda(sValor) {
   if(!sValor) {
      return 'R$ 0,00';
   }

   sValor = sValor.toString().replace(/\D/g, '');

   let iValorNumerico = parseInt(sValor, 10);

   if(isNaN(iValorNumerico)) {
      return 'R$ 0,00';
   }

   let fValorFloat = iValorNumerico / 100;

   return fValorFloat.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
}

export function normalizarValor(sValor) {
   if(!sValor) {
      return null;
   }

   const valor = parseFloat(sValor.replace(',', '.').replace(/[^\d\.]/g, ''));
   return isNaN(valor) ? null : valor.toFixed(2);
}