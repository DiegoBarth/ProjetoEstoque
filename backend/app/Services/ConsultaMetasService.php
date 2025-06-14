<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ConsultaMetasService {

   public static function consultarTodasMetas() {
      $aMetas = DB::table('metas')->orderBy('metipo')->get();

      $aResultados = [];

      foreach($aMetas as $oMeta) {
         $aResultado = self::consultarMeta($oMeta->mecodigo);

         if(count($aResultado)) {
            $aResultado['iCodigo']      = $oMeta->mecodigo;
            $aResultado['sDescricao']   = $oMeta->medescricao ?? 'Meta';
            $aResultado['iTipo']        = $oMeta->metipo;
            $aResultado['sDataInicial'] = $oMeta->medata_inicio;
            $aResultado['sDataFinal']   = $oMeta->medata_fim;
   
            $aResultados[] = $aResultado;
         }
      }

      return $aResultados;
   }

   public static function consultarMeta($iMeta) {
      $oMeta = DB::table('metas')->where('mecodigo', $iMeta)->first();

      if(!$oMeta) {
         return ['sMensagem' => 'Meta não encontrada'];
      }

      $sDataInicial = $oMeta->medata_inicio;
      $sDataFinal   = $oMeta->medata_fim;

      switch($oMeta->metipo) {
         case 1: // Valor total
            $dados = DB::selectOne("
               SELECT SUM(vendas.vevalor_total) AS valor_total
                 FROM vendas
                WHERE vendas.vedata_hora_venda::DATE BETWEEN ? AND ?
            ", [$sDataInicial, $sDataFinal]);

            return [
               'fValorTotal'  => (float) $dados->valor_total,
               'fValorMeta'   => (float) $oMeta->mevalor_meta,
               'bAtingiuMeta' => $dados->valor_total >= $oMeta->mevalor_meta
            ];

         case 2: // Quantidade total
            $dados = DB::selectOne("
               SELECT SUM(itens_vendas.ivquantidade) AS quantidade_total
                 FROM vendas
                 JOIN itens_vendas
                   ON itens_vendas.vecodigo = vendas.vecodigo
                WHERE vendas.vedata_hora_venda::DATE BETWEEN ? AND ?
            ", [$sDataInicial, $sDataFinal]);

            return [
               'iQuantidadeTotal' => (int) $dados->quantidade_total,
               'iQuantidadeMeta'  => (int) $oMeta->mequantidade_meta,
               'bAtingiuMeta'     => $dados->quantidade_total >= $oMeta->mequantidade_meta
            ];

         case 3: // Valor + Quantidade
            $dados = DB::selectOne("
               SELECT SUM(vendas.vevalor_total)       AS valor_total,
                      SUM(itens_vendas.ivquantidade) AS quantidade_total
                 FROM vendas
                 JOIN itens_vendas
                   ON itens_vendas.vecodigo = vendas.vecodigo
                WHERE vendas.vedata_hora_venda::DATE BETWEEN ? AND ?
                ", [$sDataInicial, $sDataFinal]);

            return [
               'fValorTotal'      => (float) $dados->valor_total,
               'fValorMeta'       => (float) $oMeta->mevalor_meta,
               'iQuantidadeTotal' => (int) $dados->quantidade_total,
               'iQuantidadeMeta'  => (int) $oMeta->mequantidade_meta,
               'bAtingiuMeta'     => $dados->valor_total >= $oMeta->mevalor_meta && $dados->quantidade_total >= $oMeta->mequantidade_meta
            ];

         case 4: // Valor por usuário
         case 5: // Quantidade por usuário
         case 6: // Valor + Quantidade por usuário
            return self::getMetaPorUsuario($oMeta);

         case 7: // Valor por produto
         case 8: // Quantidade por produto
         case 9: // Valor + Quantidade por produto
            return self::getMetaPorProduto($oMeta);

         default:
            return ['erro' => 'Tipo de meta inválido.'];
      }
   }

   private static function getMetaPorUsuario($oMeta) {
      $aDados = DB::table('metas_usuarios')
         ->join('usuarios',          'usuarios.usucodigo',   '=', 'metas_usuarios.usucodigo')
         ->join('vendas',            'vendas.usucodigo',     '=', 'usuarios.usucodigo')
         ->leftJoin('itens_vendas', 'itens_vendas.vecodigo', '=', 'vendas.vecodigo')
         ->where('metas_usuarios.mecodigo', $oMeta->mecodigo)
         ->whereBetween(DB::raw('DATE(vendas.vedata_hora_venda)'), [$oMeta->medata_inicio, $oMeta->medata_fim])
         ->groupBy('usuarios.usucodigo', 'usuarios.usunome')
         ->select(
            'usuarios.usucodigo',
            'usuarios.usunome',
            DB::raw('SUM(vendas.vevalor_total)      AS valor_total'),
            DB::raw('SUM(itens_vendas.ivquantidade) AS quantidade_total')
         )
         ->get();

      $aDados = $aDados->map(function ($oDados) use ($oMeta) {
         return [
            'iUsuario'         => $oDados->usucodigo,
            'sUsuario'         => $oDados->usunome,
            'fValorTotal'      => (float) $oDados->valor_total,
            'fValorMeta'       => (float) $oMeta->mevalor_meta,
            'iQuantidadeTotal' => (int) $oDados->quantidade_total,
            'iQuantidadeMeta'  => (int) $oMeta->mequantidade_meta,
            'bAtingiuMeta'     => match ((int) $oMeta->metipo) {
               4 => $oDados->valor_total      >= $oMeta->mevalor_meta,
               5 => $oDados->quantidade_total >= $oMeta->mequantidade_meta,
               6 => $oDados->valor_total      >= $oMeta->mevalor_meta && $oDados->quantidade_total >= $oMeta->mequantidade_meta,
            }
         ];
      });

      if(count($aDados)) {
         return $aDados[0];
      }

      return $aDados;
   }

   private static function getMetaPorProduto($oMeta) {
      $aDados = DB::table('metas_produtos')
         ->join('produtos',     'produtos.procodigo',     '=', 'metas_produtos.procodigo')
         ->join('itens_vendas', 'itens_vendas.procodigo', '=', 'produtos.procodigo')
         ->join('vendas',       'vendas.vecodigo',        '=', 'itens_vendas.vecodigo')
         ->where('metas_produtos.mecodigo', $oMeta->mecodigo)
         ->whereBetween(DB::raw('DATE(vendas.vedata_hora_venda)'), [$oMeta->medata_inicio, $oMeta->medata_fim])
         ->groupBy('produtos.procodigo', 'produtos.pronome')
         ->select(
            'produtos.procodigo',
            'produtos.pronome',
            DB::raw('SUM(itens_vendas.ivsubtotal)   AS valor_total'),
            DB::raw('SUM(itens_vendas.ivquantidade) AS quantidade_total')
         )
         ->get();

      $aDados = $aDados->map(function ($oDados) use ($oMeta) {
         return [
            'iProduto'         => $oDados->procodigo,
            'sProduto'         => $oDados->pronome,
            'fValorTotal'      => (float) $oDados->valor_total,
            'fValorMeta'       => (float) $oMeta->mevalor_meta,
            'iQuantidadeTotal' => (int) $oDados->quantidade_total,
            'iQuantidadeMeta'  => (int) $oMeta->mequantidade_meta,
            'bAtingiuMeta'     => match ((int) $oMeta->metipo) {
               7 => $oDados->valor_total      >= $oMeta->mevalor_meta,
               8 => $oDados->quantidade_total >= $oMeta->mequantidade_meta,
               9 => $oDados->valor_total      >= $oMeta->mevalor_meta && $oDados->quantidade_total >= $oMeta->mequantidade_meta,
            }
         ];
      });

      if(count($aDados)) {
         return $aDados[0];
      }

      return $aDados;
   }

}