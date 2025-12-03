<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Aqui chamamos o nosso semeador de produtos
        $this->call([
            BaseProductSeeder::class,
        ]);
        
        // (Opcional) Se quiser criar um usuário admin padrão sempre que resetar o banco:
        // \App\Models\User::factory()->create([
        //     'name' => 'Admin User',
        //     'email' => 'admin@communify.com',
        //     'password' => bcrypt('password'),
        //     'is_admin' => true,
        // ]);
    }
}