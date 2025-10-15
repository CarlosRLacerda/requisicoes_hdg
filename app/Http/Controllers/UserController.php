<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;
    
    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = $this->userService->listUsers($search);
        
        return view('users.index', [
            'users' => $users
        ]);
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();

        $this->userService->registerUser($data);

        return redirect()->route('users.index')->with('success', 'Usuário cadastrado com sucesso');
    }

    public function edit(Request $request, User $user)
    {
        try {
            $data = $request->all();

            $this->userService->updateUser($user, $data);
    
            return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso');
        } catch(\Exception) {
            return redirect()->route('users.index')->with('error', 'Erro interno ao editar usuário');
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();

            return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso');
        } catch(\Exception) {
            return redirect()->route('users.index')->with('error', 'Erro interno ao excluir usuário');
        }
    }
}
