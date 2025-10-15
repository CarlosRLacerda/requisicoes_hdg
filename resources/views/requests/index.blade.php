<x-app-layout>
    @if (session('success'))
        <div id="toast" class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg" style="text-transform: uppercase;">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                document.getElementById('toast').remove();
            }, 3000);
        </script>
    @endif
    @if (session('error'))
        <div id="toast" class="fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded shadow-lg" style="text-transform: uppercase;">
            {{ session('error') }}
        </div>
        <script>
            setTimeout(() => {
                document.getElementById('toast').remove();
            }, 3000);
        </script>
    @endif
  <div class="py-12 px-6">
    <div class="max-w-7xl mx-auto bg-white shadow-xl rounded-2xl p-6">
      <h2 class="text-3xl font-bold text-gray-800 uppercase mb-6 flex justify-center">
        REQUISIÇÕES
      </h2>

      <div class="flex justify-between mb-6">
        <div class="flex-1 rounded-lg shadow p-4 mx-2 card-hover" style="background-color: #bfdbfe; color: #1e40af; margin-right: 5px">
          <h3 class="text-lg font-semibold card-hover">Pendentes</h3>
          <p class="text-2xl font-bold card-hover">{{ $pendingRequestsCount }}</p>
        </div>
        <div class="flex-1 rounded-lg shadow p-4 mx-2 card-hover" style="background-color: #bbf7d0; color: #166534; margin-right: 5px">
          <h3 class="text-lg font-semibold">Aprovadas</h3>
          <p class="text-2xl font-bold">{{ $approvedRequestsCount }}</p>
        </div>
        <div class="flex-1 rounded-lg shadow p-4 mx-2 card-hover" style="background-color: #fecaca; color: #991b1b; margin-right: 5px">
          <h3 class="text-lg font-semibold">Reprovadas</h3>
          <p class="text-2xl font-bold">{{ $refusedRequestsCount }}</p>
        </div>
        <div class="flex-1 rounded-lg shadow p-4 mx-2 card-hover" style="background-color: #e5e7eb; color: #374151; margin-right: 5px">
          <h3 class="text-lg font-semibold">Total</h3>
          <p class="text-2xl font-bold">{{ $pagination['total'] }}</p>
        </div>
      </div>

      <div class="flex items-center justify-between mb-6">
        <div class="w-1/3">
          <form method="GET" action="{{ route('request.index') }}" class="flex">
              <input 
                  type="text" 
                  name="search" 
                  value="{{ $search ?? '' }}" 
                  placeholder="Buscar por código, item ou usuário..." 
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
        
        <div class="flex space-x-4">
          <form method="GET" action="{{ route('request.index') }}" id="statusFilterForm" class="flex">
              <select 
                  name="status" 
                  class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                  onchange="resetStatusFilter(this)"
              >
                  <option value="" {{ request('status') == '' ? 'selected' : '' }}>Todas</option>
                  <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                  <option value="aprovada" {{ request('status') == 'aprovada' ? 'selected' : '' }}>Aprovada</option>
                  <option value="reprovada" {{ request('status') == 'reprovada' ? 'selected' : '' }}>Reprovada</option>
              </select>
          </form>
            <a 
                  href="{{ route('request.export') }}" 
                  class="px-4 py-2 bg-gray-500 text-white font-semibold rounded-md shadow hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500"
              >
                EXPORTAR
            </a>
        </div>
        
      </div>

      <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-3 text-sm font-semibold text-gray-700 text-center">CÓDIGO</th>
              <th class="px-4 py-3 text-sm font-semibold text-gray-700 text-center">USUÁRIO</th>
              <th class="px-4 py-3 text-sm font-semibold text-gray-700 text-center">ITEM</th>
              <th class="px-4 py-3 text-sm font-semibold text-gray-700 text-center">SETOR</th>
              <th class="px-4 py-3 text-sm font-semibold text-gray-700 text-center">QUANTIDADE</th>
              <th class="px-4 py-3 text-sm font-semibold text-gray-700 text-center">STATUS</th>
              <th class="px-4 py-3 text-sm font-semibold text-gray-700 text-center">AÇÃO</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($requests as $request)
              <tr class="bg-white hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-800" style="text-align: center;">{{ $request->item->cod }}</td>
                <td class="px-4 py-3 text-sm text-gray-800" style="text-align: center;">{{ $request->user->name ?? ''}}</td>
                <td class="px-4 py-3 text-sm text-gray-800" style="text-align: center;">{{ $request->item->item }}</td>
                <td class="px-4 py-3 text-sm text-gray-800" style="text-align: center;">{{ $request->setor }}</td>
                <td class="px-4 py-3 text-sm text-gray-800" style="text-align: center;">{{ $request->qtd }}</td>
                <td class="px-4 py-3 text-sm text-gray-800" style="text-align: center;">{{ $request->status }}</td>
                <td class="px-4 py-3 text-sm text-blue-600 underline" style="text-align: center;">
                  <button data-id="{{ $request->id }}" onclick="handleAvaliar(this)" class="text-blue-600 underline">AVALIAR</button>
                </td>
              </tr>
            @endforeach

          </tbody>
        </table>
        <div class="mt-4">
          <nav class="flex flex-wrap justify-between items-center">
              <div class="flex flex-wrap gap-2">
                  @for ($i = 1; $i <= $pagination['lastPage']; $i++)
                      <a href="{{ request()->url() }}?page={{ $i }}" 
                        class="px-4 py-2 {{ $pagination['currentPage'] == $i ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded hover:bg-blue-600">
                          {{ $i }}
                      </a>
                  @endfor
              </div>
          </nav>
       </div>
      </div>
    </div>
  </div>
  <div id="modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-1/3">
      <h3 class="text-lg font-semibold mb-4">Avaliar Requisição</h3>
      <p class="mb-6">Deseja aprovar ou recusar esta requisição?</p>
      <div class="flex justify-end space-x-4">
      <form id="avaliarForm" method="POST" action="">
            @csrf
            @method('POST')
            <div class="flex justify-end space-x-4">
                <button 
                    class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600"
                    data-action="reprovada"
                    onclick="submitAvaliarForm(this)"
                >
                    RECUSAR
                </button>
                <button 
                    class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600"
                    data-action="aprovada"
                    onclick="submitAvaliarForm(this)"
                >
                    APROVAR
                </button>
            </div>
        </form>
      </div>
    </div>
  </div>
  <style>
        .card-hover {
            transition: transform 0.3s ease;
        }

        .card-hover:hover {
            transform: scale(1.02);
        }
  </style>
  <script>
    function handleAvaliar(button) {
      const id = button.getAttribute('data-id');
      openModal(`/r/${id}/avaliar`);
    }
    function openModal(actionUrl) {
      const avaliarForm = document.getElementById('avaliarForm');
      avaliarForm.action = actionUrl;
      document.getElementById('modal').classList.remove('hidden');
    }

    function closeModal() {
      document.getElementById('modal').classList.add('hidden');
    }
    function resetStatusFilter(selectElement) {
        const form = document.getElementById('statusFilterForm');
        if (selectElement.value === '') {
            const url = new URL(form.action, window.location.origin);
            url.searchParams.delete('status');
            window.location.href = url.toString();
        } else {
            form.submit();
        }
    }
    function submitAvaliarForm(button) {
      const form = document.getElementById('avaliarForm');
      const action = button.getAttribute('data-action');

      const actionInput = document.createElement('input');
      actionInput.type = 'hidden';
      actionInput.name = 'status';
      actionInput.value = action;
      form.appendChild(actionInput);

      form.submit();
      }
  </script>
</x-app-layout>