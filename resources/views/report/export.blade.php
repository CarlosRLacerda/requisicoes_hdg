<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Requisições e Materiais</title>
    <style>
        body { font-family: Arial, sans-serif; color: #222; margin: 0; padding: 0; }
        .header { background: #2563eb; color: #fff; padding: 24px 0; text-align: center; }
        .section { margin: 32px 40px; }
        .section-title { font-size: 1.3rem; color: #2563eb; margin-bottom: 12px; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; }
        .summary-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .summary-table th, .summary-table td { border: 1px solid #e5e7eb; padding: 10px 16px; text-align: center; }
        .summary-table th { background: #f1f5f9; }
        .chart { text-align: center; margin: 24px 0; }
        .footer { text-align: right; font-size: 0.9rem; color: #888; margin: 40px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório de Requisições e Materiais</h1>
        <p>{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Gráfico de Requisições</div>
        <div class="chart">
            @if(!empty($chartImage))
                <img src="{{ $chartImage }}" alt="Gráfico de Requisições" style="width: 100%; max-width: 600px;">
            @else
                <p>Gráfico não disponível.</p>
            @endif
        </div>
    </div>

    <div class="section">
        <div class="section-title">Resumo de Requisições</div>
        <table class="summary-table">
            <tr>
                <th>Aceitas</th>
                <th>Recusadas</th>
                <th>Total</th>
            </tr>
            <tr>
                <td>{{ $approvedRequestsCount }}</td>
                <td>{{ $refusedRequestsCount }}</td>
                <td>{{ $approvedRequestsCount +  $refusedRequestsCount + $pendingRequestsCount }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Resumo de Materiais</div>
        <table class="summary-table">
            <tr>
                <th>Disponíveis</th>
                <th>Indisponíveis</th>
                <th>Total</th>
            </tr>
            <tr>
                <td>{{ $availableItemsCount }}</td>
                <td>{{ $unavailableItemsCount }}</td>
                <td>{{ $availableItemsCount + $unavailableItemsCount }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Relatório gerado automaticamente pelo sistema em {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>