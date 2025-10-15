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
        MATERIAIS
      </h2>

      <div class="flex justify-between mb-6">
        <div class="flex-1 rounded-lg shadow p-4 mx-2 card-hover" style="background-color: #bbf7d0; color: #166534; margin-right: 5px">
          <h3 class="text-lg font-semibold">DISPONÍVEL</h3>
          <p class="text-2xl font-bold">{{ $totalDisponiveis = $availableItems->total() }}</p>
        </div>
        <div class="flex-1 rounded-lg shadow p-4 mx-2 card-hover" style="background-color: #fecaca; color: #991b1b; margin-right: 5px">
          <h3 class="text-lg font-semibold">INDISPONÍVEL</h3>
          <p class="text-2xl font-bold">{{ $totalIndisponiveis = $unavailableItems->total() }}</p>
        </div>
        <div class="flex-1 rounded-lg shadow p-4 mx-2 card-hover" style="background-color: #e5e7eb; color: #374151; margin-right: 5px">
          <h3 class="text-lg font-semibold">TOTAL</h3>
          <p class="text-2xl font-bold"> {{ $totalDisponiveis + $totalIndisponiveis  }}</p>
        </div>
      </div>

      <div class="flex items-center justify-between mb-6">
    <div class="w-1/3">
        <form method="GET" action="{{ route('itens.index') }}" class="flex">
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

    <div class="flex items-center space-x-4">
            <form method="GET" action="{{ route('itens.index') }}" id="statusFilterForm" class="flex">
                <select 
                    name="status" 
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    onchange="resetStatusFilter(this)"
                >
                    <option value="" {{ request('status') == '' ? 'selected' : '' }}>Todos</option>
                    <option value="disponivel" {{ request('status') == 'disponivel' ? 'selected' : '' }}>Disponível</option>
                    <option value="indisponivel" {{ request('status') == 'indisponivel' ? 'selected' : '' }}>Indisponível</option>
                </select>
            </form>
            <button 
                onclick="openCadastroModal()" 
                class="px-4 py-2 bg-green-500 text-white font-semibold rounded-md shadow hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500"
            >
                ADICIONAR
            </button>
            <a 
                href="{{ route('itens.export') }}" 
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
              <th class="px-4 py-3 text-sm font-semibold text-gray-700 text-center">ITEM</th>
              <th class="px-4 py-3 text-sm font-semibold text-gray-700 text-center">UNIDADE</th>
              <th class="px-4 py-3 text-sm font-semibold text-gray-700 text-center">QUANTIDADE</th>
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
                    <td class="px-4 py-3 text-sm text-gray-800" style="text-align: center;">
                        <button 
                            type="button" 
                            class="text-blue-600 underline hover:text-blue-800 focus:outline-none"
                            data-id="{{ $item->id }}"
                            data-cod="{{ $item->cod }}"
                            data-item="{{ $item->item }}"
                            data-unidade="{{ $item->unidade }}"
                            data-qtd="{{ $item->qtd }}"
                            onclick="openEdicaoModal(this)"
                        >
                            EDITAR
                        </button>
                        /
                        <button 
                          type="button" 
                          class="text-red-600 underline hover:text-red-800 focus:outline-none"
                          data-id="{{ $item->id }}" 
                          onclick="handleDelete(this)"
                      >
                          EXCLUIR
                      </button>
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
  <div id="cadastroModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-1/3">
      <h3 class="text-lg font-semibold mb-4">Cadastrar Item</h3>
      <form method="POST" action="{{ route('itens.create') }}">
        @csrf
        <div class="mb-4">
          <label for="cod" class="block text-sm font-medium text-gray-700">Código</label>
          <input 
            type="text" 
            id="cod"
            name="cod" 
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Código do item"
          />
        </div>
        <div class="mb-4">
          <label for="item" class="block text-sm font-medium text-gray-700">Item</label>
          <input 
            type="text" 
            id="item"
            name="item"
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Nome do item"
          />
        </div>
        <div class="mb-4">
          <label for="unidade" class="block text-sm font-medium text-gray-700">Unidade</label>
          <input 
            type="text" 
            id="unidade"
            name="unidade"
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Ex: Pacote, Caixa"
          />
        </div>
        <div class="mb-4">
          <label for="qtd" class="block text-sm font-medium text-gray-700">Quantidade</label>
          <input 
            type="number" 
            id="qtd"
            name="qtd"
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Quantidade"
          />
        </div>
        <div class="flex justify-end space-x-4">
          <button 
            type="button" 
            onclick="closeCadastroModal()" 
            class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600"
          >
            Cancelar
          </button>
          <button 
            type="submit" 
            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
          >
            Salvar
          </button>
        </div>
      </form>
    </div>
  </div>
  <div id="edicaoModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-1/3">
        <h3 class="text-lg font-semibold mb-4">Editar Item</h3>
        <form method="POST" action="" id="editForm">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="editCod" class="block text-sm font-medium text-gray-700">Código</label>
                <input 
                    type="text" 
                    id="editCod"
                    name="cod"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>
            <div class="mb-4">
                <label for="editItem" class="block text-sm font-medium text-gray-700">Item</label>
                <input 
                    type="text" 
                    id="editItem"
                    name="item"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>
            <div class="mb-4">
                <label for="editUnidade" class="block text-sm font-medium text-gray-700">Unidade</label>
                <input 
                    type="text" 
                    id="editUnidade"
                    name="unidade"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>
            <div class="mb-4">
                <label for="editQtd" class="block text-sm font-medium text-gray-700">Quantidade</label>
                <input 
                    type="number" 
                    id="editQtd"
                    name="qtd"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>
            <div class="flex justify-end space-x-4">
                <button 
                    type="button" 
                    onclick="closeEdicaoModal()" 
                    class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600"
                >
                    Cancelar
                </button>
                <button 
                    type="submit" 
                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
                >
                    Atualizar
                </button>
            </div>
        </form>
    </div>
  </div>
  <div id="exclusaoModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-1/3">
        <h3 class="text-lg font-semibold mb-4">Excluir Item</h3>
        <p class="mb-6">Tem certeza de que deseja excluir este item? Esta ação não pode ser desfeita.</p>
        <form id="deleteForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="flex justify-end space-x-4">
                <button 
                    type="button" 
                    onclick="closeExclusaoModal()" 
                    class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600"
                >
                    Cancelar
                </button>
                <button 
                    type="submit" 
                    class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600"
                >
                    Excluir
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
        function openCadastroModal(button = null) {
            const modal = document.getElementById('cadastroModal');
            modal.classList.remove('hidden');
        }
        function closeCadastroModal() {
            document.getElementById('cadastroModal').classList.add('hidden');
        }
        function openEdicaoModal(button) {
            const modal = document.getElementById('edicaoModal');
            const form = document.getElementById('editForm');

            form.action = `/i/${button.getAttribute('data-id')}`;
            document.getElementById('editCod').placeholder = button.getAttribute('data-cod');
            document.getElementById('editItem').placeholder = button.getAttribute('data-item');
            document.getElementById('editUnidade').placeholder = button.getAttribute('data-unidade');
            document.getElementById('editQtd').placeholder = button.getAttribute('data-qtd');

            document.getElementById('editCod').value = '';
            document.getElementById('editItem').value = '';
            document.getElementById('editUnidade').value = '';
            document.getElementById('editQtd').value = '';

            modal.classList.remove('hidden');
        }

        function closeEdicaoModal() {
            const modal = document.getElementById('edicaoModal');
            modal.classList.add('hidden');
        }
        function handleDelete(button) {
            const id = button.getAttribute('data-id');
            openExclusaoModal(`/i/${id}`);
        }
        function openExclusaoModal(actionUrl) {
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = actionUrl;
            document.getElementById('exclusaoModal').classList.remove('hidden');
        }
        function closeExclusaoModal() {
            document.getElementById('exclusaoModal').classList.add('hidden');
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
  </script>
</x-app-layout>