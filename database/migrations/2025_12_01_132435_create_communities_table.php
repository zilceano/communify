<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('communities', function (Blueprint $table) {
            $table->id();
            // O Dono da Comunidade (Se o usuário for deletado, a comunidade some)
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->unique(); 
            
            $table->string('name');
            $table->string('slug')->unique(); // ex: communify.com/c/minha-comunidade
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable(); // Banner
            $table->string('profile_image')->nullable(); // Logo
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('communities');
    }
};