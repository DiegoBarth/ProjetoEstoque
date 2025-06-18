<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Venda;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class RelatoriosController extends Controller {

   public function gerarRelatorioVendas() {
      $iTotalVendido = 0;

      $aVendas = Venda::select([
            'clientes.clinome as sCliente',
            'usuarios.usunome as sVendedor',
            'vedesconto as iDesconto',
            'vevalor_total as iValorTotal',
            'vedata_hora_venda as sDataVenda'
         ])
         ->leftJoin('clientes', 'vendas.clicodigo', '=', 'clientes.clicodigo')
         ->leftJoin('usuarios', 'vendas.usucodigo', '=', 'usuarios.usucodigo')
         ->get();

      foreach($aVendas as $oVenda) {
         $iTotalVendido += $oVenda->iValorTotal;
      }

      $aDados = [
         'iQuantidadeVendas' => sizeof($aVendas),
         'iTotalVendido'     => $iTotalVendido,
         'aVendas'           => $aVendas
      ];      

      $pdf = Pdf::loadView('pdf.relatorioVendas', $aDados);

      return $pdf->download('relatorio.pdf');
   }

   public function gerarRelatorioProdutos() {
      $iProdutosEstoqueBaixo  = 0;
      $iProdutosEstoqueZerado = 0;

      $aProdutos = Produto::select([
         'produtos.procodigo                                    as iProduto',
         'produtos.pronome                                      as sProduto',
         'fornecedores.fornome_fantasia                         as sFornecedor',
         'produtos.proestoque                                   as iQuantidadeEstoque',
         DB::raw('COALESCE(produtos.proestoque_minimo_ideal, 0) as "iQuantidadeIdeal"'),
         'produtos.procusto                                     as iValorCusto',
         DB::raw('COALESCE(produtos.provalor_desconto, 0)       as "iValorDesconto"'),
         'produtos.provalor                                     as iValorVenda',
         DB::raw('
            produtos.provalor - (produtos.procusto + coalesce(produtos.provalor_desconto, 0)) as "iLucro"
         '),
         DB::raw('
            CASE WHEN produtos.proestoque = 0 THEN 0
                 WHEN produtos.proestoque_minimo_ideal IS NOT NULL 
                  AND produtos.proestoque < produtos.proestoque_minimo_ideal THEN 1
                 ELSE 2
             END AS "iSituacaoEstoque"
         ')
      ])
      ->join('fornecedores', 'fornecedores.forcodigo', '=', 'produtos.forcodigo')
      ->get();

      foreach($aProdutos as $oProduto) {
         if($oProduto->iSituacaoEstoque == 1) {
            $iProdutosEstoqueBaixo++;
         }

         if($oProduto->iSituacaoEstoque == 0) {
            $iProdutosEstoqueZerado++;
         }
      }

      $aDados = [
         $iProdutosEstoqueBaixo,
         $iProdutosEstoqueZerado,
         $aProdutos
      ];

      return $aDados;

      $pdf = Pdf::loadView('pdf.relatorioVendas', $aDados);

      return $pdf->download('relatorio.pdf');
   }

   public function gerarRelatorioVendaPorProdutos() {      
      $iTotalVendasProduto = 0;
      $aDadosRelatorio = [];

      $aDados = Venda::select([
         'vendas.vecodigo      as iVenda',
         'vendas.vevalor_total as iValorTotal',
         DB::raw("
            CASE WHEN vendas.vesituacao = 1 THEN 'Aberto'
      	        WHEN vendas.vesituacao = 2 THEN 'Finalizada'
      	        WHEN vendas.vesituacao = 3 THEN 'Cancelada'
             END AS \"sSituacao\"
         "),
         DB::raw('COALESCE(vendas.vedesconto, 0) as "iDesconto"'),
         'vendas.vedata_hora_venda',
         'usuarios.usunome',
         'clientes.clinome',
         'clientes.clicpf',
         'produtos.procodigo as iProduto',
         'produtos.pronome   as sProduto'
      ])
      ->join("itens_vendas", "itens_vendas.vecodigo", '=', 'vendas.vecodigo')
      ->join("produtos", "produtos.procodigo",        '=', 'itens_vendas.procodigo')
      ->join("usuarios", "usuarios.usucodigo",        '=', 'vendas.usucodigo')
      ->join("clientes", "clientes.clicodigo",        '=', 'vendas.clicodigo')
      ->orderBy("vendas.vecodigo")
      ->get();

      $iProdutoAtual = null;
      foreach($aDados as $oDado) {
         if(empty($iProdutoAtual) || $oDado->iProduto != $iProdutoAtual) {
            $iProdutoAtual = $oDado->iProduto;
            $iTotalVendasProduto = 0;
            $aVendasProduto = [];
         }

         $aVendasProduto[] = [
            $oDado
         ];
         $iTotalVendasProduto++;

         $aDadosRelatorio[$oDado->iProduto] = [
            'iTotalVendasProduto' => $iTotalVendasProduto,
            'aVendas' => $aVendasProduto
         ];
      }

      return $aDadosRelatorio;
   }

   public function gerarRelatorioVendasPorVendedor() {      
      $iTotalVendasVendedor  = 0;
      $iTotalVendidoVendedor = 0;
      $aDadosRelatorio       = [];

      $aDados = Venda::select([
         'vendas.vecodigo      as iVenda',
         'vendas.vevalor_total as iValorTotal',
         DB::raw("
            CASE WHEN vendas.vesituacao = 1 THEN 'Aberto'
      	        WHEN vendas.vesituacao = 2 THEN 'Finalizada'
      	        WHEN vendas.vesituacao = 3 THEN 'Cancelada'
             END AS \"sSituacao\"
         "),
         DB::raw('COALESCE(vendas.vedesconto, 0) as "iDesconto"'),
         'vendas.vedata_hora_venda',
         'usuarios.usucodigo as iUsuario',
         'usuarios.usunome as sUsuario',
      ])
      ->leftJoin('usuarios', 'usuarios.usucodigo', '=', 'vendas.usucodigo')      
      ->get();

      $iVendedorAtual = null;
      foreach($aDados as $oDado) {
         if(empty($iVendedorAtual) || $oDado->iUsuario != $iVendedorAtual) {
            $iVendedorAtual = $oDado->iUsuario;
            $iTotalVendasVendedor = 0;
            $aVendasVendedor = [];
         }

         $aVendasVendedor[] = [
            $oDado
         ];
         $iTotalVendasVendedor++;
         $iTotalVendidoVendedor += $oDado->iValorTotal;
         
         $aDadosRelatorio[$oDado->iUsuario] = [
            'iTotalVendasVendedor' => $iTotalVendasVendedor,
            'iTotalVendidoVendedor' => $iTotalVendidoVendedor,
            'aVendas' => $aVendasVendedor
         ];                 
      }

      return $aDadosRelatorio;
   }

   public function gerarRelatorioVendasPorCliente() {
      $iTotalVendasCliente   = 0;
      $iValorComprasCliente  = 0;
      $aDadosRelatorio       = [];

      $aDados = Venda::select([
         'vendas.vecodigo      as iVenda',
         'vendas.vevalor_total as iValorTotal',
         DB::raw("
            CASE WHEN vendas.vesituacao = 1 THEN 'Aberto'
      	        WHEN vendas.vesituacao = 2 THEN 'Finalizada'
      	        WHEN vendas.vesituacao = 3 THEN 'Cancelada'
             END AS \"sSituacao\"
         "),
         'vendas.vesituacao as iSituacao',
         DB::raw('COALESCE(vendas.vedesconto, 0) as "iDesconto"'),
         'vendas.vedata_hora_venda',
         'clientes.clicodigo as iCliente',
         'clientes.clinome   as sCliente',
         'clientes.clicpf    as sCpf'
      ])
      ->leftJoin('clientes', 'clientes.clicodigo', '=', 'vendas.clicodigo')
      ->get();

      $iClienteAtual = null;
      foreach($aDados as $oDado) {
         if(empty($iClienteAtual) || $oDado->iCliente != $iClienteAtual) {
            $iClienteAtual = $oDado->iCliente;
            $iTotalVendasCliente = 0;
            $aVendasCliente = [];
         }

         $aVendasCliente[] = [
            $oDado
         ];
         $iTotalVendasCliente++;

         if($oDado->iSituacao == 2) {
            $iValorComprasCliente += $oDado->iValorTotal;
         }
         
         $aDadosRelatorio[$oDado->iCliente] = [
            'iTotalVendasVendedor' => $iTotalVendasCliente,
            'iValorComprasCliente' => $iValorComprasCliente,
            'aVendas' => $aVendasCliente
         ];                 
      }

      return $aDadosRelatorio;
   }

   public function gerarRelatorioProdutoPorFornecedor() {      
      $aDadosRelatorio = [];

      $aDados = Produto::select([
         'produtos.procodigo                                    as iProduto',
         'produtos.pronome                                      as sProduto',
         'fornecedores.forcodigo                                as iFornecedor',
         'fornecedores.fornome_fantasia                         as sFornecedor',
         'produtos.proestoque                                   as iQuantidadeEstoque',
         DB::raw('COALESCE(produtos.proestoque_minimo_ideal, 0) as "iQuantidadeIdeal"'),
         'produtos.procusto                                     as iValorCusto',
         DB::raw('COALESCE(produtos.provalor_desconto, 0)       as "iValorDesconto"'),
         'produtos.provalor                                     as iValorVenda',
         DB::raw('
            produtos.provalor - (produtos.procusto + coalesce(produtos.provalor_desconto, 0)) as "iLucro"
         '),
         DB::raw('
            CASE WHEN produtos.proestoque = 0 THEN 0
                 WHEN produtos.proestoque_minimo_ideal IS NOT NULL 
                  AND produtos.proestoque < produtos.proestoque_minimo_ideal THEN 1
                 ELSE 2
             END AS "iSituacaoEstoque"
         ')
      ])
      ->join('fornecedores', 'fornecedores.forcodigo', '=', 'produtos.forcodigo')
      ->get();

      $iFornecedorAtual = null;
      foreach($aDados as $oDado) {
         if(empty($iFornecedorAtual) || $oDado->iFornecedor != $iFornecedorAtual) {
            $iFornecedorAtual = $oDado->iFornecedor;
            $aProdutosFornecedor = [];
         }

         $aProdutosFornecedor[] = [
            $oDado
         ];
         
         $aDadosRelatorio[$oDado->iFornecedor] = [
            'aProdutos' => $aProdutosFornecedor
         ];                 
      }

      return $aDadosRelatorio;
   }

}