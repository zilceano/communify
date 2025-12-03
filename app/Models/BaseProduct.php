<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseProduct extends Model
{
    use HasFactory;

    // Campos que podem ser preenchidos
    protected $fillable = [
        'name',
        'description',
        'base_price',
        'options_json',
    ];

    // Avisa o Laravel que 'options_json' deve ser tratado como um Array/JSON automaticamente
    protected $casts = [
        'base_price' => 'decimal:2',
        'options_json' => 'array', 
    ];
}