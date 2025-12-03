<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Community extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'cover_image',
        'profile_image',
    ];

    /**
     * Evento automático: Quando criar a comunidade, gera o 'slug' (URL amigável)
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($community) {
            $community->slug = Str::slug($community->name);
        });
    }

    // --- RELACIONAMENTOS ---

    // Pertence a um Dono
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Tem muitos posts
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // Tem muitos produtos
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // --- A FUNÇÃO QUE FALTAVA ---
    // Uma comunidade pode ser SEGUIDA POR VÁRIOS usuários
    public function followers()
    {
        // Relação N-para-N usando a tabela 'follows'
        return $this->belongsToMany(User::class, 'follows', 'community_id', 'user_id');
    }
}