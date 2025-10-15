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
        USUÁRIOS
      </h2>
      <div class="flex items-center justify-between mb-6">
      <div class="w-1/3">
          <form method="GET" action="{{ route('users.index') }}" class="flex">
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
        
        <div class="flex space-x-4">
          <button 
            onclick="openCadastroModal()" 
            class="px-4 py-2 bg-gray-500 text-white font-semibold rounded-md shadow hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500"
          >
            ADICIONAR
          </button>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-3 text-sm font-semibold text-gray-700 text-center">ID</th>
              <th class="px-4 py-3 text-sm font-semibold text-gray-700 text-center">NOME</th>
              <th class="px-4 py-3 text-sm font-semibold text-gray-700 text-center">EMAIL</th>
              <th class="px-4 py-3 text-sm font-semibold text-gray-700 text-center">PAPEL</th>
              <th class="px-4 py-3 text-sm font-semibold text-gray-700 text-center">AÇÃO</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($users as $user)
              @if($user->id != Auth::id())
                <tr class="bg-white hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-800" style="text-align: center;">{{ $user->id }}</td>
                    <td class="px-4 py-3 text-sm text-gray-800" style="text-align: center;">{{ $user->name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-800" style="text-align: center;">{{ $user->email }}</td>
                    <td class="px-4 py-3 text-sm text-gray-800" style="text-align: center;">{{ $user->getRoleNames()[0] }}</td>
                    <td class="px-4 py-3 text-sm text-gray-800" style="text-align: center;">
                        <button 
                            type="button" 
                            class="text-blue-600 underline hover:text-blue-800 focus:outline-none"
                            data-id="{{ $user->id }}"
                            data-name="{{ $user->name }}"
                            data-email="{{ $user->email }}"
                            data-role="{{ $user->id }}"
                            onclick="openEdicaoModal(this)"
                        >
                            EDITAR
                        </button> 
                        /
                        <a 
                            href="{{ route('users.delete', $user->id) }}"
                            type="button" 
                            class="text-blue-600 underline hover:text-blue-800 focus:outline-none"
                        >
                            EXCLUIR
                        </a> 
                    </td>
                </tr>
              @endif
            @endforeach
        </tbody>
        </table>
        
      </div>
    </div>
  </div>

  <div id="cadastroModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-1/3">
      <h3 class="text-lg font-semibold mb-4">Cadastrar Usuário</h3>
      <form method="POST" action="{{ route('users.create') }}">
        @csrf
        <div class="mb-4">
          <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
          <input 
            type="text" 
            id="name"
            name="name"
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Nome do usuário"
            required
          />
        </div>
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input 
            type="text" 
            id="email"
            name="email"
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Email do usuário"
            required
          />
        </div>
        <div class="mb-4">
          <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
          <input 
            type="text" 
            id="password"
            name="password"
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            value="{{ env('DEFAULT_PASSWORD') }}"
            disabled
          />
        </div>
        <div class="mb-4">
            <label for="papel" class="block text-sm font-medium text-gray-700">Acesso</label>
            <select 
                id="role" 
                name="role" 
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                <option value="" disabled selected>Selecione uma papel</option>
                <option value="default">Padrão</option>
                <option value="almo">Almoxerifado</option>
                <option value="admin">Administrador</option>
            </select>
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
      <h3 class="text-lg font-semibold mb-4">Editar Usuário</h3>
      <form method="POST" action="" id="editForm">
        @csrf
        <div class="mb-4">
          <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
          <input 
            type="text" 
            id="editName"
            name="name"
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Nome do usuário"
          />
        </div>
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input 
            type="text" 
            id="editEmail"
            name="email"
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Email do usuário"
          />
        </div>
        <div class="mb-4">
          <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
          <input 
            type="text" 
            id="editPassword"
            name="password"
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>
        <div class="mb-4">
            <label for="papel" class="block text-sm font-medium text-gray-700">Acesso</label>
            <select 
                id="editRole" 
                name="role" 
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                <option value="" disabled selected>Selecione uma papel</option>
                <option value="default">Padrão</option>
                <option value="almo">Almoxerifado</option>
                <option value="admin">Administrador</option>
            </select>
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

            form.action = `/u/${button.getAttribute('data-id')}/edit`;
            document.getElementById('editName').placeholder = button.getAttribute('data-name');
            document.getElementById('editEmail').placeholder = button.getAttribute('data-email');
            document.getElementById('editRole').placeholder = button.getAttribute('data-role');
            modal.classList.remove('hidden');
        }

        function closeEdicaoModal() {
            const modal = document.getElementById('edicaoModal');
            modal.classList.add('hidden');
        }
  </script>
</x-app-layout>