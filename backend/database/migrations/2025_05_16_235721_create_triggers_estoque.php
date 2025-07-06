c<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

   public function up() {
      DB::unprepared('
        CREATE OR REPLACE FUNCTION fn_abater_estoque()
        RETURNS TRIGGER AS $$
        BEGIN
           IF NEW.vesituacao = 2 AND OLD.vesituacao IS DISTINCT FROM 2 THEN
              UPDATE produtos p
              SET proestoque = proestoque - iv.ivquantidade
              FROM itens_vendas iv
              WHERE iv.vecodigo = NEW.vecodigo
              AND iv.procodigo = p.procodigo;
           END IF;
           RETURN NEW;
        END;
        $$ LANGUAGE plpgsql;

        CREATE TRIGGER trg_abater_estoque
        AFTER UPDATE OF vesituacao ON vendas
        FOR EACH ROW
        EXECUTE FUNCTION fn_abater_estoque();
    ');

      DB::unprepared('
        CREATE OR REPLACE FUNCTION fn_repor_estoque()
        RETURNS TRIGGER AS $$
        DECLARE
           v_procodigo INT;
        BEGIN
           SELECT procodigo INTO v_procodigo
           FROM itens_vendas
           WHERE ivcodigo = NEW.ivcodigo;

           UPDATE produtos
           SET proestoque = proestoque + NEW.idquantidade
           WHERE procodigo = v_procodigo;

           UPDATE itens_vendas
           SET ivquantidade = ivquantidade - NEW.idquantidade
           WHERE ivcodigo = NEW.ivcodigo;

           RETURN NEW;
        END;
        $$ LANGUAGE plpgsql;

        CREATE TRIGGER trg_repor_estoque
        AFTER INSERT ON itens_devolucoes
        FOR EACH ROW
        EXECUTE FUNCTION fn_repor_estoque();
    ');
   }

   public function down() {
      DB::unprepared('DROP TRIGGER IF EXISTS trg_abater_estoque ON vendas;');
      DB::unprepared('DROP FUNCTION IF EXISTS fn_abater_estoque();');
      DB::unprepared('DROP TRIGGER IF EXISTS trg_repor_estoque ON itens_devolucoes;');
      DB::unprepared('DROP FUNCTION IF EXISTS fn_repor_estoque();');
   }

};