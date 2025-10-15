<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('cod')->unique();
            $table->string('item');
            $table->string('unidade');
            $table->integer('qtd');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('itens');
    }
};
