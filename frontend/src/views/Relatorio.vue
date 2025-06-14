<template>
   <div class="card-principal w-[calc(100vw-50px)] h-[calc(100vh-50px)] m-[25px] rounded-xl overflow-hidden">
      <Consulta sTitulo="Relatórios" :bMostraBotao="false">
         <template #gridConsulta>
            <div class="px-[calc(10vw-70px)] flex flex-col justify-between h-[calc(100vw-1150px)]">               
               <div>
               <Campo v-model="iTipo" @change="limparFiltros" sTitulo="Tipo" bLabel sTipo="select" class="w-50 mb-5" :aOpcoes="aOpcoesRelatorio" />
               
               <h1 v-if="iTipo" class="text-2xl mb-5">Filtros</h1>

               <div v-if="iTipo == 1">
                  <div class="flex gap-10">
                     <Campo class="mb-3 w-50" sTitulo="Data Inicial" bLabel sTipo="date" v-model="oFiltros.sDataInicial"/>
                     <Campo class="mb-3 w-50" sTitulo="Data Final" bLabel sTipo="date" v-model="oFiltros.sDataFinal"/>
                     <Campo class="mb-3 w-50" sTitulo="Situação" bLabel sTipo="select" v-model="oFiltros.iSituacao" :aOpcoes="[{iValor: 1, sDescricao: 'Ativo'}, {iValor:2, sDescricao: 'Inativo'}]" />
                  </div>
                  <Grid sTitulo="Fornecedores" :aCabecalhos="['', 'Fornecedor', 'Nome Fantasia']" :bDataTable="false">
                     <tr v-for="(oFornecedor, iIndice) of aFornecedores" :key="iIndice">
                        <td class="p-2"><Campo sTipo="checkbox" :value="oFornecedor.forcodigo" @change="alteraFiltro('fornecedor', oFornecedor.forcodigo)"/></td>
                        <td class="p-2">{{ oFornecedor.forcodigo }}</td>
                        <td class="p-2">{{ oFornecedor.fornome_fantasia }}</td>
                     </tr>
                  </Grid>
               </div>

               <div v-if="iTipo == 2">
                  <div class="flex gap-10">
                     <Campo class="mb-3 w-50" sTitulo="Data Inicial" bLabel v-model="oFiltros.sDataInicial" sTipo="date" />
                     <Campo class="mb-3 w-50" sTitulo="Data Final" bLabel   v-model="oFiltros.sDataFinal" sTipo="date" />
                     <Campo class="mb-3 w-50" sTitulo="Situação" bLabel     v-model="oFiltros.iSituacao" sTipo="select" :aOpcoes="[{iValor: 1, sDescricao: 'Aberta'}, {iValor:2, sDescricao: 'Finalizada'}, {iValor: 3, sDescricao: 'Cancelada'}]" />
                  </div>
                  <div class="flex gap-10">
                     <Grid class="w-1/2" sTitulo="Clientes" :aCabecalhos="['', 'Cliente', 'Nome do Cliente']" :bDataTable="false">
                        <tr v-for="(oCliente, iIndice) of aClientes" :key="iIndice">
                           <td class="p-2"><Campo sTipo="checkbox" :value="oCliente.clicodigo" @change="alteraFiltro('cliente', oCliente.iProduto)"/></td>
                           <td class="p-2">{{ oCliente.clicodigo }}</td>
                           <td class="p-2">{{ oCliente.clinome }}</td>
                        </tr>
                     </Grid>
                     <Grid class="w-1/2" sTitulo="Usuário (Vendedor)" :aCabecalhos="['', 'Usuário', 'Nome de Usuário']" :bDataTable="false">
                        <tr v-for="(oUsuario, iIndice) of aUsuarios" :key="iIndice">
                           <td class="p-2"><Campo sTipo="checkbox" :value="oUsuario.usucodigo" @change="alteraFiltro('usuario', oUsuario.usucodigo)"/></td>
                           <td class="p-2">{{ oUsuario.usucodigo }}</td>
                           <td class="p-2">{{ oUsuario.usunome }}</td>
                        </tr>
                     </Grid>
                  </div>
               </div>               

               <div v-if="iTipo >= 3 && iTipo < 6">
                  <div class="flex gap-10">
                     <Campo class="mb-3 w-50" sTitulo="Data Inicial (Venda)" v-model="oFiltros.sDataInicial" bLabel sTipo="date" />
                     <Campo class="mb-3 w-50" sTitulo="Data Final (Venda)"   v-model="oFiltros.sDataFinal" bLabel sTipo="date" />                                          
                     <Campo class="mb-3 w-50" sTitulo="Situação (Venda)"     v-model="oFiltros.iSituacao" bLabel sTipo="select" :aOpcoes="[{iValor: 1, sDescricao: 'Aberta'}, {iValor:2, sDescricao: 'Finalizada'}, {iValor: 3, sDescricao: 'Cancelada'}]" />
                  </div>
                  <Grid v-if="iTipo == 3" sTitulo="Produtos" :aCabecalhos="['', 'Produto', 'Nome do Produto']" :bDataTable="false">
                     <tr v-for="(oProduto, iIndice) of aProdutos" :key="iIndice">
                        <td class="p-2"><Campo sTipo="checkbox" :value="oProduto.iProduto" @change="alteraFiltro('produto', oProduto.iProduto)"/></td>
                        <td class="p-2">{{ oProduto.iProduto }}</td>
                        <td class="p-2">{{ oProduto.sNome }}</td>
                     </tr>
                  </Grid>
                  <Grid v-if="iTipo == 4" sTitulo="Usuário (Vendedor)" :aCabecalhos="['', 'Usuário', 'Nome de Usuário']" :bDataTable="false">
                     <tr v-for="(oUsuario, iIndice) of aUsuarios" :key="iIndice">
                        <td class="p-2"><Campo sTipo="checkbox" :value="oUsuario.usucodigo" @change="alteraFiltro('usuario', oUsuario.usucodigo)"/></td>
                        <td class="p-2">{{ oUsuario.usucodigo }}</td>
                        <td class="p-2">{{ oUsuario.usunome }}</td>
                     </tr>
                  </Grid>
                  <Grid v-if="iTipo == 5" sTitulo="Clientes" :aCabecalhos="['', 'Cliente', 'Nome do Cliente']" :bDataTable="false">
                     <tr v-for="(oCliente, iIndice) of aClientes" :key="iIndice">
                        <td class="p-2"><Campo sTipo="checkbox" :value="oCliente.clicodigo" @change="alteraFiltro('cliente', oCliente.iProduto)"/></td>
                        <td class="p-2">{{ oCliente.clicodigo }}</td>
                        <td class="p-2">{{ oCliente.clinome }}</td>
                     </tr>
                  </Grid>
               </div>    

               <div v-if="iTipo == 6">
                  <div class="flex gap-10">
                     <Grid class="w-1/2" sTitulo="Produtos" :aCabecalhos="['', 'Produto', 'Nome do Produto']" :bDataTable="false">
                        <tr v-for="(oProduto, iIndice) of aProdutos" :key="iIndice">
                           <td class="p-2"><Campo sTipo="checkbox" :value="oProduto.iProduto" @change="alteraFiltro('produto', oProduto.iProduto)"/></td>
                           <td class="p-2">{{ oProduto.iProduto }}</td>
                           <td class="p-2">{{ oProduto.sNome }}</td>
                        </tr>
                     </Grid>
                     <Grid class="w-1/2" sTitulo="Fornecedores" :aCabecalhos="['', 'Fornecedor', 'Nome do Fornecedor']" :bDataTable="false">
                        <tr v-for="(oFornecedor, iIndice) of aFornecedores" :key="iIndice">
                           <td class="p-2"><Campo sTipo="checkbox" :value="oFornecedor.forcodigo" @change="alteraFiltro('fornecedor', oFornecedor.forcodigo)"/></td>
                           <td class="p-2">{{ oFornecedor.forcodigo }}</td>
                           <td class="p-2">{{ oFornecedor.fornome_fantasia }}</td>
                        </tr>
                     </Grid>
                  </div>
               </div>        
               </div>    

               <Botao v-if="iTipo" @click="emitirRelatorio" sLargura="w-1/10" sClasses="mt-5" sCor="botaoVerde" sTexto="Emitir"/>
            </div>
         </template>
      </Consulta>
   </div>
</template>
<script setup>
//#region Componentes
import Consulta from '../components/UI/Consulta.vue'
import Campo from '../components/UI/Campo.vue'
import Grid from '../components/UI/Grid.vue'
import Botao from '../components/UI/Botao.vue'
//#endregion

import { onBeforeMount, ref } from 'vue'
import { useUsuarioStore } from '../stores/usuarioStore'
import { useClienteStore } from '../stores/clienteStore'
import { useFornecedorStore } from '../stores/fornecedorStore'
import { useProdutoStore } from '../stores/produtoStore'

const oUsuariosStore     = useUsuarioStore();
const oClientesStore     = useClienteStore();
const oFornecedoresStore = useFornecedorStore();
const oProdutosStore     = useProdutoStore();

onBeforeMount(async () => {
   aUsuarios.value     = await oUsuariosStore.getUsuarios();
   aClientes.value     = await oClientesStore.getClientes();
   aFornecedores.value = await oFornecedoresStore.getFornecedores();
   aProdutos.value     = await oProdutosStore.getProdutos();
})

const aUsuarios        = ref([]);
const aClientes        = ref([]);
const aFornecedores    = ref([]);
const aProdutos        = ref([]);
const iTipo            = ref('');   
const oFiltros         = ref({});
const aOpcoesRelatorio = ref([
   {
      iValor: 1,
      sDescricao: 'Produtos'   
   },   
   {
      iValor: 2,
      sDescricao: 'Vendas'   
   },   
   {
      iValor: 3,
      sDescricao: 'Vendas X Produto'
   },
   {
      iValor: 4,
      sDescricao: 'Vendas X Vendedor'
   },
   {
      iValor: 5,
      sDescricao: 'Vendas X Cliente'
   },
   {
      iValor: 6,
      sDescricao: 'Produto X Fornecedor'
   }   
])

function emitirRelatorio() {  
   console.log(oFiltros.value)
   return  
   switch(iTipo.value) {
      case 1:
         oRelatorioStore.emitirRelatorioProdutos(tratarFiltros());
         break;
      case 2:
         oRelatorioStore.emitirRelatorioVendas(tratarFiltros());
         break;
      case 3:
         oRelatorioStore.emitirRelatorioVendasPorProduto(tratarFiltros());
         break;
      case 4:
         oRelatorioStore.emitirRelatorioVendasPorVendedor(tratarFiltros());
         break;
      case 5:
         oRelatorioStore.emitirRelatorioVendasPorCliente(tratarFiltros());
         break;
      case 6:
         oRelatorioStore.emitirRelatorioProdutosPorFornecedor(tratarFiltros());
         break;
   }
}

function limparFiltros() {
   oFiltros.value = {};   
}

function alteraFiltro(sFiltro, iCodigo) {
   switch(sFiltro) {
      case 'fornecedor':
         if(!oFiltros.value.aFornecedores) {
            oFiltros.value.aFornecedores = [];            
         }
         oFiltros.value.aFornecedores.push(iCodigo);
         break;
      case 'cliente':
         if(!oFiltros.value.aClientes) {
            oFiltros.value.aClientes = [];            
         }
         oFiltros.aClientes.push(iCodigo);
         break;
      case 'usuario':
         if(!oFiltros.value.aUsuarios) {
            oFiltros.value.aUsuarios = [];            
         }
         oFiltros.aUsuarios.push(iCodigo);
         break;
      case 'produto':
         if(!oFiltros.value.aProdutos) {
            oFiltros.value.aProdutos = [];            
         }
         oFiltros.aProdutos.push(iCodigo);
         break;
   }
}

</script>