<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function listUsers(?string $search = null)
    {
        if ($search) {
            return User::where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%");
            })->get();
        }
    
        return User::all();
    }

    public function registerUser(array $data)
    {
        try {
            $this->canRegisterUser($data);

            $user = User::create([...$data, 'password' => bcrypt(env('DEFAULT_PASSWORD'))]);

            $user->assignRole($data['role']);
        } catch(\Exception $e) {
            return redirect()->route('users.index')->with('error', $e->getMessage());
        }
    }

    public function updateUser(User $user, array $data)
    {
        try {
            $this->canRegisterUser($data);

            $user->name = $this->field($data, 'name') ?: $user->name;
            $user->email = $this->field($data, 'email') ?: $user->email;

            if ($this->field($data, 'password')) {
                $user->password = bcrypt($this->field($data, 'password'));
            }

            if ($this->field($data, 'role')) {
                $user->syncRoles($this->field($data, 'role'));
            }

            $user->save();
            
        } catch(\Exception $e) {
            return redirect()->route('users.index')->with('error', $e->getMessage());
        }
    }

    private function canRegisterUser(array $data)
    {
        if(isset($data['email']) && User::whereEmail($data['email'])->exists()) {
            throw new \Exception('O email informada já existe!');
        }
    }

    private function field(array $data, string $key)
    {
        return array_key_exists($key, $data) && !is_null($data[$key]) ? $data[$key] : false;
    }
}