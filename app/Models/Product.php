<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'community_id',
        'base_product_id',
        'name',
        'slug',
        'description',
        'profit',
        'image_mockup',
        'file_artwork',
        'digital_link',
        'is_active',
    ];

    protected $casts = [
        'profit' => 'decimal:2',
        'is_active' => 'boolean',
    ];
    
    // Gera o slug automaticamente (ex: Camiseta Legal -> camiseta-legal)
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }

    // --- RELACIONAMENTOS ---

    // Pertence a uma comunidade
    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    // É baseado em um Produto Base (Molde)
    public function baseProduct()
    {
        return $this->belongsTo(BaseProduct::class);
    }
}