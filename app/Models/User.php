<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Campos que podem ser preenchidos no cadastro/edição
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin', 
        'bio',      
        'avatar',   
        'full_name', 'cpf', 'address_street', 'address_number', 
        'address_complement', 'address_city', 'address_state', 'address_zip'
    ];

    /**
     * Campos escondidos (segurança)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversão de tipos
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    // --- RELACIONAMENTOS ---

    // Um usuário (Criador) PODE TER UMA comunidade
    public function community()
    {
        return $this->hasOne(Community::class);
    }

    // Um usuário (Comprador) PODE TER VÁRIOS pedidos
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Um usuário (Leitor) pode SEGUIR VÁRIAS comunidades
    public function follows()
    {
        return $this->belongsToMany(Community::class, 'follows', 'user_id', 'community_id');
    }
}