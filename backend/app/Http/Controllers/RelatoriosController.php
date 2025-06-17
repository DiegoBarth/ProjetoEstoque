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
         'produtos.procodigo               as "iProduto"',
         'produtos.pronome                 as "sProduto"',
         'fornecedores.fornome_fantasia    as "sFornecedor"',
         'produtos.proestoque              as "iQuantidadeEstoque"',
         'produtos.proestoque_minimo_ideal as "iQuantidadeIdeal"',
         'produtos.procusto                as "iValorCusto"',
         'produtos.provalor_desconto       as "iValorDesconto"',
         'produtos.provalor                as "iValorVenda"',
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
             END AS sSituacao
         "),
         DB::raw('COALESCE(vendas.vedesconto, 0)'),
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

}