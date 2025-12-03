<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BaseProduct;
use Illuminate\Support\Facades\Schema; // <--- Importamos o Schema

class BaseProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Desabilita a verificação de chaves estrangeiras temporariamente
        Schema::disableForeignKeyConstraints();

        // 2. Agora podemos limpar a tabela sem erro
        BaseProduct::truncate();

        // 3. Reabilita a verificação
        Schema::enableForeignKeyConstraints();

        // --- Criação dos Produtos ---

        // 1. Camiseta (Com Variações de Tamanho e Cor)
        BaseProduct::create([
            'name' => 'Camiseta Básica',
            'description' => 'Camiseta 100% algodão, malha penteada.',
            'base_price' => 35.00,
            'options_json' => [
                'Tamanho' => ['P', 'M', 'G', 'GG'],
                'Cor' => ['Preto', 'Branco']
            ]
        ]);

        // 2. Cartaz A4
        BaseProduct::create([
            'name' => 'Cartaz A4',
            'description' => 'Impressão em papel couchê 180g, tamanho 21x29.7cm.',
            'base_price' => 15.00,
            'options_json' => null
        ]);

        // 3. Cartaz A3
        BaseProduct::create([
            'name' => 'Cartaz A3',
            'description' => 'Impressão em papel couchê 180g, tamanho 29.7x42cm.',
            'base_price' => 25.00,
            'options_json' => null
        ]);

        // 4. Caneca
        BaseProduct::create([
            'name' => 'Caneca de Cerâmica',
            'description' => 'Caneca branca 325ml.',
            'base_price' => 22.00,
            'options_json' => null
        ]);

        // 5. E-book
        BaseProduct::create([
            'name' => 'E-book / Arquivo Digital',
            'description' => 'Produto digital para download (PDF, EPUB, etc).',
            'base_price' => 5.00,
            'options_json' => null
        ]);
        
        // 6. Adesivos
        BaseProduct::create([
            'name' => 'Kit de Adesivos (5 un.)',
            'description' => '5 adesivos de vinil 10x10cm.',
            'base_price' => 12.00,
            'options_json' => null
        ]);
    }
}