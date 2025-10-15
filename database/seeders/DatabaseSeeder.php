<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Request;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
   
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'default']);
        Role::create(['name' => 'almo']);

        User::factory()->create([
            'name' => 'Daniel Admin',
            'email' => 'admin@hdg.com',
            'password' => bcrypt('1234') //1me#*T93RJ^*5!x!D1,+
        ])->assignRole('admin');

        User::factory()->create([
            'name' => 'Daniel Padrão',
            'email' => 'daniel@hdg.com',
            'password' => bcrypt('1234') //1me#*T93RJ^*5!x!D1,+
        ])->assignRole(roles: 'default');

        User::factory()->create([
            'name' => 'Daniel Almoxarifado',
            'email' => 'almo@hdg.com',
            'password' => bcrypt('1234') //1me#*T93RJ^*5!x!D1,+
        ])->assignRole(roles: 'almo');

        // User::factory(10)->create();

        Item::factory(100)->create();
        
        Request::factory(count: 50)->create();
    }
}
