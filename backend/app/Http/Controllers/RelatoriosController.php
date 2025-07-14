<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelatoriosController extends Controller {   

   /**
    * @var \FPDF
    */
   private $oPdf;

   public function gerarRelatorioVendas(Request $oRequest) {            
      $iTotalVendido = 0;
      $sFiltros      = '';      

      $oQuery = Venda::select([
            'clientes.clicodigo as iCliente',            
            'clientes.clinome as sCliente',
            'usuarios.usucodigo as iVendedor',
            'usuarios.usunome as sVendedor',
            'vedesconto as iDesconto',
            'vevalor_total as iValorTotal',
            'vedata_hora_venda as sDataVenda'
         ])                                             
         ->leftJoin('clientes', 'vendas.clicodigo', '=', 'clientes.clicodigo')
         ->leftJoin('usuarios', 'vendas.usucodigo', '=', 'usuarios.usucodigo')
         ->orderBy('usuarios.usunome')->orderBy('clientes.clinome')->orderBy('vedata_hora_venda');

      if($oRequest->sDataInicial) {
         $sFiltros .= "Data Inicial: {$oRequest->sDataInicial}".PHP_EOL;
         $oQuery->where('vedata_hora_venda', '>=', "{$oRequest->sDataInicial}");                                    
      }

      if($oRequest->sDataFinal) {
         $sFiltros .= "Data Final: {$oRequest->sDataFinal}".PHP_EOL;
         $oQuery->where('vedata_hora_venda', '<=', $oRequest->sDataFinal);
      }

      if($oRequest->iSituacao) {
         $sSituacao = $this->getSituacaoVenda($oRequest->iSituacao);

         $sFiltros .= "Situação: {$sSituacao}".PHP_EOL;
         $oQuery->where('vesituacao', intval($oRequest->iSituacao));
      }

      if($oRequest->aClientes) {
         $sClientes = implode(', ', $oRequest->aClientes);
         
         $sFiltros .= "Clientes: {$sClientes}".PHP_EOL;
         $oQuery->whereIn('vendas.clicodigo', $oRequest->aClientes);
      }

      if($oRequest->aUsuarios) {
         $sUsuarios = implode(', ', $oRequest->aUsuarios);
         
         $sFiltros .= "Vendedores: {$sUsuarios}".PHP_EOL;
         $oQuery->whereIn('vendas.usucodigo', $oRequest->aUsuarios);
      }
      
      $aVendas = $oQuery->get();
      
      $this->oPdf = new \FPDF();
      $this->oPdf->AddPage();
      $this->criarCabecalho('Relatório de Vendas');

      $iVendedor             = 0;
      $iVendasVendedor       = 0;
      $fTotalVendidoVendedor = 0;      
      $this->criarFiltros(utf8_decode($sFiltros));
      foreach($aVendas as $oVenda) {
         if($iVendedor && $iVendedor != $oVenda->iVendedor) {                        
            $this->oPdf->setFillColor(200, 200, 200);
            $this->setTotalizador(utf8_decode("Valor total Vendido pelo Vendedor: R$ " . number_format($fTotalVendidoVendedor, 2, ',', '.')));
            $this->setTotalizador(utf8_decode("Número total de Vendas do Vendedor: {$iVendasVendedor }"));            
            $iVendasVendedor   = 0;
            $fTotalVendidoVendedor = 0;
         }

         if($iVendedor == 0 || $iVendedor != $oVenda->iVendedor) {
            $this->oPdf->setFont('Arial', 'B', 9);
            $this->oPdf->SetFillColor(150, 150, 150);
            $this->oPdf->Cell(190, 5, "Vendedor {$oVenda->iVendedor} - [{$oVenda->sVendedor}]", 0, 1, 'L', true);            
            $this->oPdf->setFillColor(180, 180, 180);
            $this->oPdf->SetX($this->oPdf->GetX() + 3.8);
            $this->oPdf->Cell(13, 5, 'Cliente',         0, 0, 'L', true);
            $this->oPdf->Cell(80, 5, 'Nome do Cliente', 0, 0, 'L', true);
            $this->oPdf->Cell(18, 5, 'Desconto',        0, 0, 'L', true);
            $this->oPdf->Cell(18, 5, 'Valor Total',     0, 0, 'L', true);
            $this->oPdf->Cell(18, 5, 'Lucro',           0, 0, 'L', true);
            $this->oPdf->Cell(39, 5, 'Data da Venda',   0, 1, 'L', true);
         }


         $this->oPdf->setFont('Arial', '', 9);
         $this->oPdf->setFillColor(200, 200, 200);
         $this->oPdf->SetX($this->oPdf->GetX() + 3.8);
         $this->oPdf->Cell(13, 5,   $oVenda->iCliente,                                                           0, 0,  'L', true);
         $this->oPdf->Cell(80, 5,   utf8_decode($oVenda->sCliente),                                              0, 0,  'L', true);
         $this->oPdf->Cell(18, 5,  'R$' . number_format($oVenda->iDesconto, 2, ',', '.'),                        0, 0,  'L', true);
         $this->oPdf->Cell(18, 5,  'R$' . number_format($oVenda->iValorTotal, 2, ',', '.'),                      0, 0,  'L', true);
         $this->oPdf->Cell(18 , 5, 'R$' . number_format($oVenda->iValorTotal - $oVenda->iDesconto, 2, ',', '.'), 0, 0, 'L', true);
         $this->oPdf->Cell(39, 5,  date('d/m/Y', strtotime($oVenda->sDataVenda)),                                0, 1,  'L', true);         

         $iVendedor              = $oVenda->iVendedor;          
         $fTotalVendidoVendedor += $oVenda->iValorTotal;
         $iTotalVendido         += $oVenda->iValorTotal;
         $iVendasVendedor++;
      }

      $this->oPdf->setFillColor(200, 200, 200);
      $this->setTotalizador(utf8_decode("Valor total Vendido pelo Vendedor: R$ " . number_format($fTotalVendidoVendedor, 2, ',', '.')));
      $this->setTotalizador(utf8_decode("Número total de Vendas do Vendedor: {$iVendasVendedor }"));            
      $this->oPdf->setFillColor(150, 150, 150);
      $this->setTotalizador(utf8_decode("Valor total Vendido: R$ " . number_format($iTotalVendido, 2, ',', '.')));
      $this->setTotalizador(utf8_decode("Número total de Vendas: ".  sizeof($aVendas)));
      
      return response($this->oPdf->Output('S'), 200)
         ->header('Content-Type', 'application/pdf');    
   }

   public function gerarRelatorioProdutos(Request $oRequest) {
      $sFiltros = '';      
      
      $oQuery = Produto::select([
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
      ->join('fornecedores', 'fornecedores.forcodigo', '=', 'produtos.forcodigo');

      if($oRequest->sDataInicial) {
         $sFiltros .= "Data Inicial: {$oRequest->sDataInicial}".PHP_EOL;
         $oQuery->where('produtos.prodata_hora_cadastro', '>=', $oRequest->sDataInicial);
      }

      if($oRequest->sDataFinal) {
         $sFiltros .= "Data Final: {$oRequest->sDataFinal}".PHP_EOL;   
         $oQuery->where('produtos.prodata_hora_cadastro', '<=', $oRequest->sDataFinal);
      }      

      if($oRequest->aFornecedores) {
         $sFornecedores = implode(', ', $oRequest->aFornecedores);

         $sFiltros .= "Fornecedor: {$sFornecedores}".PHP_EOL;
         $oQuery->whereIn('produtos.forcodigo', $oRequest->aFornecedores);
      }

      $aProdutos = $oQuery->get();

      $this->oPdf = new \FPDF();
      $this->oPdf->AddPage('R');
      $this->criarCabecalho('Relatório de Produtos');
      
      $iFornecedor                     = 0;
      $iProdutosEstoqueBaixoFornecedor = 0;
      $iProdutosEmFaltaFornecedor      = 0;
      $iProdutosEstoqueBaixo           = 0;
      $iProdutosEmFalta                = 0;            
      $this->criarFiltros(utf8_decode($sFiltros));
      foreach($aProdutos as $oProduto) {
         if($iFornecedor && $iFornecedor != $oProduto->iFornecedor) {                        
            $this->oPdf->setFillColor(200, 200, 200);
            $this->setTotalizador(utf8_decode("Total de produtos com estoque baixo do fornecedor {$iProdutosEstoqueBaixoFornecedor}"), 195);
            $this->setTotalizador(utf8_decode("Total de produtos fora de estoque do fornecedor {$iProdutosEmFaltaFornecedor}")       , 195);
            $iProdutosEstoqueBaixoFornecedor = 0;
            $iProdutosEmFaltaFornecedor      = 0;
         }

         if($iFornecedor == 0 || $iFornecedor != $oProduto->iFornecedor) {
            $this->oPdf->setFont('Arial', 'B', 9);
            $this->oPdf->SetFillColor(150, 150, 150);
            $this->oPdf->Cell(275, 5, "Fornecedor {$oProduto->iFornecedor} - [{$oProduto->sFornecedor}]", 0, 1, 'L', true);
            $this->oPdf->setFillColor(180, 180, 180);
            $this->oPdf->SetX($this->oPdf->GetX() + 4);
            $this->oPdf->Cell(13, 5, 'Produto',                              0, 0, 'L', true);
            $this->oPdf->Cell(64, 5, 'Nome do Produto',                      0, 0, 'L', true);
            $this->oPdf->Cell(40, 5, 'Quantidade em Estoque',                0, 0, 'L', true);
            $this->oPdf->Cell(40, 5, utf8_decode('Quantidade Mínima Ideal'), 0, 0, 'L', true);
            $this->oPdf->Cell(25, 5, 'Valor de Custo',                       0, 0, 'L', true);
            $this->oPdf->Cell(32, 5, 'Valor de Desconto',                    0, 0, 'L', true);
            $this->oPdf->Cell(25, 5, 'Valor de Venda',                       0, 0, 'L', true);
            $this->oPdf->Cell(32, 5, utf8_decode('Lucro Unitário'),          0, 1, 'L', true);
         }
        

         $this->oPdf->setFont('Arial', '', 9);
         $this->oPdf->setFillColor(200, 200, 200);
         $this->oPdf->SetX($this->oPdf->GetX() + 4);
         $this->oPdf->Cell(13, 5, $oProduto->iProduto,                                                0, 0, 'L', true);
         $this->oPdf->Cell(64, 5, $oProduto->sProduto,                                                0, 0, 'L', true);
         $this->oPdf->Cell(40, 5, $oProduto->iQuantidadeEstoque,                                      0, 0, 'L', true);
         $this->oPdf->Cell(40, 5, $oProduto->iQuantidadeIdeal ?: utf8_decode('Sem Informação'),       0, 0, 'L', true);
         $this->oPdf->Cell(25, 5, "R$ " . number_format($oProduto->iValorCusto, 2, ',', '.'),         0, 0, 'L', true);
         $this->oPdf->Cell(32, 5, "R$"  . number_format($oProduto->iValorDesconto ?: 0, 2, ',', '.'), 0, 0, 'L', true);
         $this->oPdf->Cell(25, 5, "R$ " . number_format($oProduto->iValorVenda, 2, ',', '.'),         0, 0, 'L', true);
         $this->oPdf->Cell(32, 5, "R$ " . number_format($oProduto->iLucro, 2, ',', '.'),              0, 1, 'L', true);

         $iFornecedor = $oProduto->iFornecedor;
         if($oProduto->iSituacaoEstoque == 1) {
            $iProdutosEstoqueBaixoFornecedor++;
            $iProdutosEstoqueBaixo++;
         }

         if($oProduto->iSituacaoEstoque == 0) {
            $iProdutosEmFaltaFornecedor++;
            $iProdutosEmFalta++;
         }
      }

      $this->oPdf->setFillColor(200, 200, 200);
      $this->setTotalizador(utf8_decode("Total de produtos com estoque baixo do fornecedor {$iProdutosEstoqueBaixoFornecedor}"), 195);
      $this->setTotalizador(utf8_decode("Total de produtos fora de estoque do fornecedor {$iProdutosEmFaltaFornecedor}"),        195);
      $this->oPdf->setFillColor(150, 150, 150);
      $this->setTotalizador(utf8_decode("Total de produtos com estoque baixo {$iProdutosEstoqueBaixo}"),                         195);
      $this->setTotalizador(utf8_decode("Total de produtos fora de estoque {$iProdutosEmFalta}"),                                195);
      
      return response($this->oPdf->Output('S'), 200)
         ->header('Content-Type', 'application/pdf');      
   }

   public function gerarRelatorioVendasPorProdutos(Request $oRequest) {
      $oQuery = Venda::select([
         'vendas.vecodigo      as iVenda',
         'vendas.vevalor_total as iValorTotal',
         DB::raw("
            CASE WHEN vendas.vesituacao = 1 THEN 'Aberto'
      	        WHEN vendas.vesituacao = 2 THEN 'Finalizada'
      	        WHEN vendas.vesituacao = 3 THEN 'Cancelada'
             END AS \"sSituacao\"
         "),
         DB::raw('COALESCE(vendas.vedesconto, 0) as "iDesconto"'),
         'vendas.vedata_hora_venda as sDataVenda',
         'usuarios.usunome as sVendedor',
         'clientes.clinome as sCliente',
         'clientes.clicpf',
         'itens_vendas.procodigo as iProduto',
         'produtos.pronome   as sProduto'
      ])
      ->join("itens_vendas", "itens_vendas.vecodigo", '=', 'vendas.vecodigo')
      ->join("produtos", "produtos.procodigo",        '=', 'itens_vendas.procodigo')
      ->join("usuarios", "usuarios.usucodigo",        '=', 'vendas.usucodigo')
      ->join("clientes", "clientes.clicodigo",        '=', 'vendas.clicodigo')      
      ->orderBy("itens_vendas.procodigo")
      ->orderBy("vendas.vecodigo");

      $sFiltros = "";

      if($oRequest->sDataInicial) {
         $sFiltros .= "Data Inicial: {$oRequest->sDataInicial}".PHP_EOL;
         $oQuery->where('vedata_hora_venda', '>=', "{$oRequest->sDataInicial}");                                    
      }

      if($oRequest->sDataFinal) {
         $sFiltros .= "Data Final: {$oRequest->sDataFinal}".PHP_EOL;
         $oQuery->where('vedata_hora_venda', '<=', $oRequest->sDataFinal);
      }

      if($oRequest->iSituacao) {
         $sSituacao = $this->getSituacaoVenda($oRequest->iSituacao);

         $sFiltros .= "Situação: {$sSituacao}".PHP_EOL;
         $oQuery->where('vesituacao', intval($oRequest->iSituacao));
      }

      if($oRequest->aProdutos) {
         $sProdutos = implode(', ', $oRequest->aProdutos);

         $sFiltros .= "Produtos: {$sProdutos}".PHP_EOL;
         $oQuery->whereIn('itens_vendas.procodigo', $oRequest->aProdutos);
      }

      $aVendas = $oQuery->get();

      $this->oPdf = new \FPDF();
      $this->oPdf->AddPage('R');
      $this->criarCabecalho('Relatório de Vendas');
      $this->criarFiltros(utf8_decode($sFiltros));

      $iProduto            = 0;      
      $iTotalVendasProduto = 0;      
      foreach($aVendas as $oVenda) {
         if($iProduto && $iProduto != $oVenda->iProduto) {                        
            $this->oPdf->setFillColor(200, 200, 200);            
            $this->setTotalizador(utf8_decode("Número total de Vendas do Produto: {$iTotalVendasProduto }"));            
            $iTotalVendasProduto = 0;            
         }

         if($iProduto == 0 || $iProduto != $oVenda->iProduto) {
            $this->oPdf->setFont('Arial', 'B', 9);
            $this->oPdf->SetFillColor(150, 150, 150);
            $this->oPdf->Cell(275, 5, utf8_decode("Produto {$oVenda->iProduto} - [{$oVenda->sProduto}]"), 0, 1, 'L', true);            
            $this->oPdf->setFillColor(180, 180, 180);
            $this->oPdf->SetX($this->oPdf->GetX() + 3.8);
            $this->oPdf->Cell(13, 5, 'Venda',                 0, 0, 'L', true);
            $this->oPdf->Cell(18, 5, 'Valor Total',           0, 0, 'L', true);
            $this->oPdf->Cell(18, 5, 'Desconto',              0, 0, 'L', true);
            $this->oPdf->Cell(18, 5, 'Lucro',                 0, 0, 'L', true);
            $this->oPdf->Cell(18, 5, utf8_decode('Situação'), 0, 0, 'L', true);
            $this->oPdf->Cell(40, 5, 'Data da Venda',         0, 0, 'L', true);
            $this->oPdf->Cell(73.1, 5, 'Nome do Cliente',       0, 0, 'L', true);
            $this->oPdf->Cell(73.1, 5, 'Nome do Vendedor',      0, 1, 'L', true);
         }


         $this->oPdf->setFont('Arial', '', 9);
         $this->oPdf->setFillColor(200, 200, 200);
         $this->oPdf->SetX($this->oPdf->GetX() + 3.8);
         $this->oPdf->Cell(13, 5,   $oVenda->iVenda,                                                              0, 0,  'L', true);
         $this->oPdf->Cell(18, 5,  'R$' . number_format($oVenda->iValorTotal, 2, ',', '.'),                       0, 0,  'L', true);
         $this->oPdf->Cell(18, 5,  'R$' . number_format($oVenda->iDesconto, 2, ',', '.'),                         0, 0,  'L', true);
         $this->oPdf->Cell(18 , 5, 'R$' . number_format($oVenda->iValorTotal - $oVenda->iDesconto, 2, ',', '.'),  0, 0, 'L', true);
         $this->oPdf->Cell(18, 5,   utf8_decode($oVenda->sSituacao),                                              0, 0,  'L', true);
         $this->oPdf->Cell(40, 5,   date('d/m/Y', strtotime($oVenda->sDataVenda)),                                0, 0,  'L', true);         
         $this->oPdf->Cell(73.1, 5,   utf8_decode($oVenda->sCliente),                                               0, 0,  'L', true);
         $this->oPdf->Cell(73.1, 5,   utf8_decode($oVenda->sVendedor),                                              0, 1,  'L', true);

         $iProduto = $oVenda->iProduto;
         $iTotalVendasProduto++;
      }

      $this->oPdf->setFillColor(200, 200, 200);      
      $this->setTotalizador(utf8_decode("Número total de Vendas do Produto: {$iTotalVendasProduto }"));            
      $this->oPdf->setFillColor(150, 150, 150);      
      $this->setTotalizador(utf8_decode("Número total de Vendas: ".  sizeof($aVendas)));
      
      return response($this->oPdf->Output('S'), 200)
         ->header('Content-Type', 'application/pdf');      
   }   

   public function gerarRelatorioVendasPorCliente(Request $oRequest) {
      $oQuery = Venda::select([
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
      ->leftJoin('clientes', 'clientes.clicodigo', '=', 'vendas.clicodigo');

      $sFiltros = '';

      if($oRequest->sDataInicial) {
         $sFiltros .= "Data Inicial: {$oRequest->sDataInicial}".PHP_EOL;
         $oQuery->where('vedata_hora_venda', '>=', "{$oRequest->sDataInicial}");                                    
      }

      if($oRequest->sDataFinal) {
         $sFiltros .= "Data Final: {$oRequest->sDataFinal}".PHP_EOL;
         $oQuery->where('vedata_hora_venda', '<=', $oRequest->sDataFinal);
      }

      if($oRequest->iSituacao) {
         $sSituacao = $this->getSituacaoVenda($oRequest->iSituacao);

         $sFiltros .= "Situação: {$sSituacao}".PHP_EOL;
         $oQuery->where('vesituacao', intval($oRequest->iSituacao));
      }

      if($oRequest->aClientes) {
         $sClientes = implode(', ', $oRequest->aClientes);

         $sFiltros .= "Clientes: {$sClientes}".PHP_EOL;
         $oQuery->whereIn('vendas.clicodigo', $oRequest->aClientes);
      }

      $aVendas = $oQuery->get();

      $this->oPdf = new \FPDF();
      $this->oPdf->AddPage();
      $this->criarCabecalho('Relatório de Vendas Por Cliente');

      $this->oPdf->setFont('Arial', 'B', 12);
      $this->oPdf->Cell(50, 5, 'Filtros', 0, 1, 'L');
      $iCliente = 0;
      $iVendasCliente = 0;      
      $fTotalVendidoCliente = 0;
      $fTotalVendido = 0;
      $this->criarFiltros(utf8_decode($sFiltros));
      $this->oPdf->Ln(5);
      foreach($aVendas as $oVenda) {
         if($iCliente && $iCliente != $oVenda->iCliente) {                        
            $this->oPdf->setFillColor(200, 200, 200);
            $this->setTotalizador(utf8_decode("Valor total Vendido para o Cliente: R$ " . number_format($fTotalVendidoCliente, 2, ',', '.')));
            $this->setTotalizador(utf8_decode("Número total de Compras do Cliente: {$iVendasCliente }"));            
            $iVendasCliente = 0;
            $fTotalVendidoCliente = 0;
         }                    

         if($iCliente == 0 || $iCliente != $oVenda->iCliente) {
            $this->oPdf->setFont('Arial', 'B', 9);
            $this->oPdf->SetFillColor(150, 150, 150);
            $this->oPdf->Cell(190, 5, utf8_decode("Cliente {$oVenda->iCliente} - [{$oVenda->sCliente}]"), 0, 1, 'L', true);            
            $this->oPdf->setFillColor(180, 180, 180);
            $this->oPdf->SetX($this->oPdf->GetX() + 3.8);
            $this->oPdf->Cell(13, 5, 'Venda',                  0, 0, 'L', true);
            $this->oPdf->Cell(35, 5, 'Valor Total',            0, 0, 'L', true);            
            $this->oPdf->Cell(35, 5, 'Desconto',               0, 0, 'L', true);
            $this->oPdf->Cell(35, 5, 'Lucro',                  0, 0, 'L', true);
            $this->oPdf->Cell(28, 5,  utf8_decode('Situação'), 0, 0, 'L', true);
            $this->oPdf->Cell(40, 5, 'Data da Venda',          0, 1, 'L', true);
         }


         $this->oPdf->setFont('Arial', '', 9);
         $this->oPdf->setFillColor(200, 200, 200);
         $this->oPdf->SetX($this->oPdf->GetX() + 3.8);
         $this->oPdf->Cell(13, 5,   $oVenda->iVenda,                                                           0, 0,  'L', true);
         $this->oPdf->Cell(35, 5,  'R$' . number_format($oVenda->iValorTotal, 2, ',', '.'),                      0, 0,  'L', true);
         $this->oPdf->Cell(35, 5,  'R$' . number_format($oVenda->iDesconto, 2, ',', '.'),                        0, 0,  'L', true);
         $this->oPdf->Cell(35 , 5, 'R$' . number_format($oVenda->iValorTotal - $oVenda->iDesconto, 2, ',', '.'), 0, 0, 'L', true);
         $this->oPdf->Cell(28, 5,   utf8_decode($oVenda->sSituacao),                                              0, 0,  'L', true);
         $this->oPdf->Cell(40, 5,  date('d/m/Y', strtotime($oVenda->sDataVenda)),                                0, 1,  'L', true);         

         $iCliente              = $oVenda->iCliente;          
         $fTotalVendidoCliente += $oVenda->iValorTotal;
         $fTotalVendido        += $oVenda->iValorTotal;
         $iVendasCliente++;
      }            
            
      $this->oPdf->setFillColor(200, 200, 200);
      $this->setTotalizador(utf8_decode("Valor total Vendido pelo Cliente: R$ " . number_format($fTotalVendidoCliente, 2, ',', '.')));
      $this->setTotalizador(utf8_decode("Número total de Compras do Cliente: {$iVendasCliente }"));            
      $this->oPdf->setFillColor(150, 150, 150);
      $this->setTotalizador(utf8_decode("Valor total Vendido: R$ " . number_format($fTotalVendido, 2, ',', '.')));
      $this->setTotalizador(utf8_decode("Número total de Vendas: ".  sizeof($aVendas)));
      
      return response($this->oPdf->Output('S'), 200)
         ->header('Content-Type', 'application/pdf');      
   }   

   private function criarCabecalho($sTitulo) {
      $this->oPdf->Image('img/logo.png', 10, 10, 20);                     
      $this->oPdf->SetFont('Arial', 'B', 16);
      $this->oPdf->Cell($this->oPdf->GetPageWidth() - 10, 10, utf8_decode('I Fix For You'), 0, 1, 'C');                  
      $this->oPdf->Cell($this->oPdf->GetPageWidth() - 10, 10, utf8_decode($sTitulo), 0, 1, 'C');
      $this->oPdf->AliasNbPages();
      $this->oPdf->Ln(10);
   }

   private function criarFiltros($sFiltros) {
      $this->oPdf->setFont('Arial', '', 9);
      $this->oPdf->MultiCell('190', '5', $sFiltros, 0, 'L', false);
   }

   private function setTotalizador($xValor, $iMargin = null) {
      $this->oPdf->SetX($iMargin ?: 110);      
      $this->oPdf->SetFont('Arial', 'B', 9);
      return $this->oPdf->Cell('90', 5, $xValor, 0, 1, 'R', true);
   }

   private function getSituacaoVenda($iSituacao) {
      switch(intval($iSituacao)) {
         case 1:
            return 'Aberto';
         case 2: 
            return 'Finalizada';
         case 3:
            return 'Cancelada';
      }
   }

}