<?php

use App\Enums\NeedEnum;
use App\Enums\SetorEnum;
use App\Enums\StatusRequestEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $status = array_column(StatusRequestEnum::cases(), 'value');
        $necessidade = array_column(NeedEnum::cases(), 'value');
        $setores = array_column(SetorEnum::cases(), 'value');

        Schema::create('requests', function (Blueprint $table) use($status, $necessidade, $setores) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('item_id');
            $table->integer('qtd');
            $table->enum('necessidade', $necessidade)->nullable();
            $table->enum('status', $status);
            $table->enum('setor', $setores);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
