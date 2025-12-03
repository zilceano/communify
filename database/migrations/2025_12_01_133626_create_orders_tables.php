<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. O Pedido Geral
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('restrict'); // O Comprador
            
            // Status do pedido: aguardando, pago, enviado, cancelado
            $table->string('status')->default('awaiting_payment'); 
            
            $table->decimal('total_amount', 10, 2); // Preço total (produtos + doação)
            
            // Simulação de Pagamento
            $table->string('proof_of_payment')->nullable(); // Caminho do arquivo do comprovante
            
            // Doação (R$ 1,00 se marcado)
            $table->decimal('donation_amount', 10, 2)->default(0);

            $table->timestamps();
        });

        // 2. Os Itens dentro do Pedido
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict'); // O produto vendido
            
            $table->integer('quantity');
            $table->decimal('price', 10, 2); // O preço pago na hora (fixo)
            
            // Aqui salvamos o que o cliente escolheu (Ex: {"Tamanho": "M", "Cor": "Preto"})
            $table->json('selected_options_json')->nullable(); 
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};