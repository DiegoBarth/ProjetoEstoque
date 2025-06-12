<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>Relatório de Vendas</h2>
    <p>Gerado por: {{ $nome }}</p>
    <p>Data: {{ $data }}</p>

    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Vendedor</th>
                <th>Valor de Desconto (R$)</th>
                <th>Valor Total (R$)</th>
                <th>Data da Venda</th>                
            </tr>
        </thead>
        <tbody>
            @foreach ($vendas as $venda)
                <tr>
                    <td>{{ $venda->sCliente }}</td>
                    <td>{{ $venda->sVendedor }}</td>
                    <td>{{ number_format($venda->vedesconto, 2, ',', '.') }}</td>
                    <td>{{ number_format($venda->vevalor_total, 2, ',', '.') }}</td>
                    <td>{{ date('d/m/Y', strtotime($venda->vedata_hora_venda)) }}</td>                    
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
