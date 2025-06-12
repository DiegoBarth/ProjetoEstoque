<?php

namespace App\Http\Controllers;

use App\Models\Venda;
use Barryvdh\DomPDF\Facade\Pdf;

class RelatoriosController extends Controller {

   public function gerarRelatorioVendas() {
      $aVendas = Venda::leftJoin('clientes', 'vendas.clicodigo', '=', 'clientes.clicodigo')
         ->leftJoin('usuarios', 'vendas.usucodigo', '=', 'usuarios.usucodigo')
         ->select([
            'clientes.clinome as sCliente',
            'usuarios.usunome as sVendedor',
            'vedesconto',
            'vevalor_total',
            'vedata_hora_venda'
         ])
         ->get();

      $dados = [
            'nome' => 'Kauã',
            'data' => date('d/m/Y'),
            'vendas' => $aVendas
        ];

        $pdf = Pdf::loadView('pdf.relatorioVendas', $dados);

        //streak save

        return $pdf->download('relatorio.pdf');
   }

}