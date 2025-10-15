<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = [
        'cod', 'item', 'unidade', 'qtd'
    ];

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }
}
