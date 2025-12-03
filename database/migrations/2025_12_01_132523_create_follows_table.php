<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Quem segue
            $table->foreignId('community_id')->constrained()->onDelete('cascade'); // Quem é seguido
            $table->timestamps();
            
            // Impede que a pessoa siga a mesma comunidade 2 vezes
            $table->unique(['user_id', 'community_id']); 
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};