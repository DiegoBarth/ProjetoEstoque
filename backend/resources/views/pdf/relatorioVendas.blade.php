<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
   <style>
      {!! file_get_contents(public_path('..\resources\css\relatorio.css')) !!}
   </style>
</head>
<body>
   <header class="header-relatorio">
      <h1>Relatório de vendas</h1>
      <img src="{{ public_path('img\logo1.png') }}" alt="logo I Fix For You">
   </header>
   <div class="container-relatorio">
      <div class="cards">
         <div class="card" id="quantiadeVendas">
            <h1>Número de vendas: {{ $iQuantidadeVendas }}</h1>
         </div>
         <div class="card" id="totalVendas">
            <h1>Valor total vendido: {{ $iTotalVendido }}</h1>
         </div>
      </div>
      <div class="dados-relatorio">
         
      </div>      
   </div>
</body>
</html>