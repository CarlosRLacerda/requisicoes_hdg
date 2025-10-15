<?php

namespace Database\Factories;

use App\Enums\NeedEnum;
use App\Enums\SetorEnum;
use App\Enums\StatusRequestEnum;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RequestFactory extends Factory
{
    public function definition(): array
    {
        $user_id = User::pluck('id')->random();
        $item_id = Item::pluck('id')->random();
        $status = array_column(StatusRequestEnum::cases(), 'value');
        $setor = array_column(SetorEnum::cases(), 'value');
        
        return [
            'user_id' => $user_id,
            'item_id' => $item_id,
            'qtd' => fake()->numberBetween(1, 100),
            'setor' => fake()->randomElement($setor),
            'status' => fake()->randomElement($status)
        ];
    }
}
