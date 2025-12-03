<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabela do ADMIN: Os produtos "molde" (Camiseta, Caneca, etc.)
        Schema::create('base_products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ex: "Camiseta", "Cartaz A4"
            $table->text('description')->nullable();
            $table->decimal('base_price', 10, 2); // Nosso preço de custo (Ex: 25.00)
            
            // Aqui ficam as variações possíveis (Ex: Tamanho P, M, G)
            // Se for null, o produto não tem variação (ex: Caneca)
            $table->json('options_json')->nullable(); 
            
            $table->timestamps();
        });

        // 2. Tabela do CRIADOR: Os produtos à venda na loja dele
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_id')->constrained()->onDelete('cascade');
            // Se deletarmos o produto base, não deleta o produto da loja, apenas impede
            $table->foreignId('base_product_id')->constrained()->onDelete('restrict');
            
            $table->string('name'); // Ex: "Camiseta 'Café & Código'"
            $table->string('slug')->unique();
            $table->text('description');
            
            // O lucro que o Criador escolheu (Ex: 15.00)
            $table->decimal('profit', 10, 2); 
            
            // Arquivos (Caminhos das imagens)
            $table->string('image_mockup'); // Foto da loja
            $table->string('file_artwork'); // Arquivo para impressão
            $table->string('digital_link')->nullable(); // Para produtos digitais
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('base_products');
    }
};