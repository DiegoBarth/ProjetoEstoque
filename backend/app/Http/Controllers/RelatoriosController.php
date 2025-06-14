<?php

namespace App\Http\Controllers;

use App\Models\Venda;
use Barryvdh\DomPDF\Facade\Pdf;

class RelatoriosController extends Controller {

   public function gerarRelatorioVendas() {
      $aVendas = Venda::select([
            'clientes.clinome as sCliente',
            'usuarios.usunome as sVendedor',
            'vedesconto',
            'vevalor_total',
            'vedata_hora_venda'
         ])
         ->leftJoin('clientes', 'vendas.clicodigo', '=', 'clientes.clicodigo')
         ->leftJoin('usuarios', 'vendas.usucodigo', '=', 'usuarios.usucodigo')
         ->get();

      $dados = [
            'nome' => 'Kauã',
            'data' => date('d/m/Y'),
            'vendas' => $aVendas
        ];

        $pdf = Pdf::loadView('pdf.relatorioVendas', $dados);        

        return $pdf->download('relatorio.pdf');
   }

}