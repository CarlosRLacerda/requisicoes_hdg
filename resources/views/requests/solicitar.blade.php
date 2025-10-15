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
        MATERIAS
      </h2>
      <div class="flex items-center justify-between mb-6">
        <div class="w-1/3">
          <form method="GET" action="{{ route('request.solicitar') }}" class="flex">
              <input 
                  type="text" 
                  name="search" 
                  value="{{ $search ?? '' }}" 
                  placeholder="Buscar por código ou item..." 
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

      <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-3 text-sm font-semibold text-gray-700 text-center">CÓDIGO</th>
              <th class="px-4 py-3 text-sm font-semibold text-gray-700 text-center">ITEM</th>
              <th class="px-4 py-3 text-sm font-semibold text-gray-700 text-center">UNIDADE</th>
              <th class="px-4 py-3 text-sm font-semibold text-gray-700 text-center">ESTOQUE</th>
              <th class="px-4 py-3 text-sm font-semibold text-gray-700 text-center">AÇÃO</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($items as $item)
                <tr class="bg-white hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-800" style="text-align: center;">{{ $item->cod }}</td>
                    <td class="px-4 py-3 text-sm text-gray-800" style="text-align: center;">{{ $item->item }}</td>
                    <td class="px-4 py-3 text-sm text-gray-800" style="text-align: center;">{{ $item->unidade }}</td>
                    <td class="px-4 py-3 text-sm text-gray-800" style="text-align: center;">{{ $item->qtd }}</td>
                    @if($item->qtd > 0)
                    <td class="px-4 py-3 text-sm text-gray-800" style="text-align: center;">
                        <button 
                            type="button" 
                            class="text-blue-600 underline hover:text-blue-800 focus:outline-none"
                            data-id="{{ $item->id }}"
                            data-qtd="{{ $item->qtd }}"
                            onclick="openSolicitacaoModal(this)"
                        >
                            SOLICITAR
                        </button>
                    </td>
                    @else
                    <td class="px-4 py-3 text-sm text-gray-800" style="text-align: center;">INDISPONÍVEL</td>
                    @endif
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
  <div id="solicitarModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-1/3">
        <h3 class="text-lg font-semibold mb-4">Solicitar Material</h3>
        <form method="POST" action="" id="solicitarForm">
            @csrf
            @method('POST')
            <div class="mb-4">
                <label for="qtd" class="block text-sm font-medium text-gray-700">Quantidade</label>
                <input 
                    type="number" 
                    id="qtd"
                    name="qtd"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>
            <div class="mb-4">
                <label for="setor" class="block text-sm font-medium text-gray-700">Setor</label>
                <select 
                    id="setor" 
                    name="setor" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="" disabled selected>Selecione o setor</option>
                    @foreach ($setores as $setor)
                        <option value="{{ $setor->value }}">{{ $setor->value}}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end space-x-4">
                <button 
                    type="button" 
                    onclick="closeSolicitacaoModal()" 
                    class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600"
                >
                    Cancelar
                </button>
                <button 
                    type="submit" 
                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
                >
                    Solicitar
                </button>
            </div>
        </form>
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
        function openSolicitacaoModal(button) {
            const modal = document.getElementById('solicitarModal');
            const form = document.getElementById('solicitarForm');

            form.action = `/r/s/${button.getAttribute('data-id')}`;
            document.getElementById('qtd').placeholder = `Até ${button.getAttribute('data-qtd')}`;

            modal.classList.remove('hidden');
        }

        function closeSolicitacaoModal() {
            const modal = document.getElementById('solicitarModal');
            modal.classList.add('hidden');
        }
  </script>
</x-app-layout>