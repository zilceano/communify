<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total_amount',
        'proof_of_payment',
        'donation_amount',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'donation_amount' => 'decimal:2',
    ];

    // Quem comprou
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Lista de itens comprados
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}