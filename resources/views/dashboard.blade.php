<x-app-layout>
    
    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col items-center gap-y-6 mb-6">
                <a href="{{ route('request.solicitar') }}" type="button" class="button-hover flex flex-col px-4 py-2 text-base font-medium text-white inline-flex items-center rounded-lg text-center" style="background-color:rgb(8, 119, 153);">                        
                    <i class="fas fa-plus-circle w-4 h-4 text-white me-2"></i>
                    <span class="text-lg">Solicitar Material</span>
                </a>
                @if(Auth::user()->hasRole(['admin', 'almo']))
                    <div class="flex justify-center gap-x-12">
                        <a href="{{route('itens.index')}}" type="button" class="button-hover flex flex-col px-4 py-2 text-base font-medium text-white inline-flex items-center rounded-lg text-center" style="background-color:rgb(8, 119, 153);">                        
                            <i class="fas fa-boxes w-4 h-4 text-white me-2"></i>
                            <span class="text-lg">Estoque</span>
                        </a>
                        <a href="{{route('request.index')}}" type="button" class="button-hover flex flex-col px-4 py-2 text-base font-medium text-white inline-flex items-center rounded-lg text-center" style="background-color:rgb(8, 119, 153);">                        
                            <i class="fas fa-file-alt w-4 h-4 text-white me-2"></i>
                            <span class="text-lg">Requisições</span>
                        </a>
                        @if(Auth::user()->hasRole('admin'))
                        <a href="{{ route('users.index') }}" type="button" class="button-hover flex flex-col px-4 py-2 text-base font-medium text-white inline-flex items-center rounded-lg text-center" style="background-color:rgb(8, 119, 153);">                        
                            <i class="fas fa-users w-4 h-4 text-white me-2"></i>
                            <span class="text-lg">Usuários</span>
                        </a>
                        @endif
                        <form id="pdfForm" method="POST" action="{{ route('report.export') }}">
                            @csrf
                            <input type="hidden" name="chart_image" id="chartImageInput">
                            <button type="submit" class="button-hover flex flex-col px-4 py-2 text-base font-medium text-white inline-flex items-center rounded-lg text-center" style="background-color:rgb(8, 119, 153);">
                                <i class="fas fa-file-excel w-4 h-4 text-white me-2"></i>
                                <span class="text-lg">Gerar Relatório</span>
                            </button>
                        </form>
                    </div>
                @endif

            </div>
            
            @if(Auth::user()->hasRole(['admin', 'almo']))

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <canvas id="requisicoesChart" width="400" height="200"></canvas>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h2 class="text-xl font-bold mb-4">Requisições de Hoje</h2>
                    <table class="min-w-full table-auto border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-4 py-2 text-left">ITEM</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">DATA</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requestsToday['requests'] as $request)
                                <tr>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <a href="/r?search={{ $request->item->cod }}" class="text-blue-500 hover:underline">{{ $request->item->item }}</a>
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">{{ \Carbon\Carbon::parse($request->created_at)->format('d/m/y') }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $request->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        <nav class="flex flex-wrap justify-between items-center">
                            <div class="flex flex-wrap gap-2">
                                @for ($i = 1; $i <= $requestsToday['pagination']['lastPage']; $i++)
                                    <a href="{{ request()->url() }}?page1={{ $i }}" 
                                        class="px-4 py-2 {{ $requestsToday['pagination']['currentPage'] == $i ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded hover:bg-blue-600">
                                        {{ $i }}
                                    </a>
                                @endfor
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h2 class="text-xl font-bold mb-4">Requisições dos Últimos 7 Dias</h2>
                    <table class="min-w-full table-auto border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-4 py-2 text-left">ITEM</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">DATA</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requestsLast7Days['requests'] as $request)
                                <tr>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <a href="/r?search={{ $request->item->cod }}" class="text-blue-500 hover:underline">{{ $request->item->item }}</a>
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">{{ \Carbon\Carbon::parse($request->created_at)->format('d/m/y') }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $request->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        <nav class="flex flex-wrap justify-between items-center">
                            <div class="flex flex-wrap gap-2">
                                @for ($i = 1; $i <= $requestsLast7Days['pagination']['lastPage']; $i++)
                                    <a href="{{ request()->url() }}?page2={{ $i }}" 
                                        class="px-4 py-2 {{ $requestsLast7Days['pagination']['currentPage'] == $i ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded hover:bg-blue-600">
                                        {{ $i }}
                                    </a>
                                @endfor
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(Auth::user()->hasRole('default'))
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                        <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold mb-4">Minhas Solicitações</h2>
                        <div class="w-1/3">
                        <form method="GET" action="{{ route('dashboard') }}" class="flex">
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ $search ?? '' }}" 
                                placeholder="Buscar..." 
                                class="w-full px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            <button 
                                type="submit" 
                                class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-r-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                Buscar
                            </button>
                        </form>
                        </div>
                    </div>
                    <table class="min-w-full table-auto border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-4 py-2 text-left">CÓDIGO</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">ITEM</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">QUANTIDADE</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">DATA</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($myRequests['requests'] as $request)
                                <tr>
                                    <td class="border border-gray-300 px-4 py-2">
                                        {{ $request->item->cod }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        {{ $request->item->item }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        {{ $request->qtd }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">{{ \Carbon\Carbon::parse($request->created_at)->format('d/m/y') }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $request->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        <nav class="flex flex-wrap justify-between items-center">
                            <div class="flex flex-wrap gap-2">
                                @for ($i = 1; $i <= $myRequests['pagination']['lastPage']; $i++)
                                    <a href="{{ request()->url() }}?page1={{ $i }}" 
                                        class="px-4 py-2 {{ $myRequests['pagination']['currentPage'] == $i ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded hover:bg-blue-600">
                                        {{ $i }}
                                    </a>
                                @endfor
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .button-hover {
            transition: transform 0.3s ease;
        }

        .button-hover:hover {
            transform: translateY(-5px);
        }
    </style>
    @if(Auth::user()->hasRole(['admin', 'almo']))
    <script>
        const requestsStats = @json($requestsStats);

        const acceptedData = [];
        const refusedData = [];

        for (let month = 1; month <= 12; month++) {
            acceptedData.push(requestsStats[month]?.accepted || 0);
            refusedData.push(requestsStats[month]?.refused || 0);
        }

        const ctx = document.getElementById('requisicoesChart').getContext('2d');
        const requisicoesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                datasets: [
                    {
                        label: 'Aceitas',
                        data: acceptedData,
                        backgroundColor: 'rgba(75, 192, 75, 0.7)',
                        borderColor: 'rgba(75, 192, 75, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Recusadas',
                        data: refusedData,
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        document.getElementById('pdfForm').addEventListener('submit', function(e) {
            const canvas = document.getElementById('requisicoesChart');
            document.getElementById('chartImageInput').value = canvas.toDataURL('image/png');
        });
    </script>
    @endif
</x-app-layout>