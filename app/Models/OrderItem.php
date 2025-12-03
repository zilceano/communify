<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // Essa tabela não precisa de data de criação/edição
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'selected_options_json', // Aqui fica o JSON da variação escolhida
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'selected_options_json' => 'array', // Transforma JSON em Array automaticamente
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}