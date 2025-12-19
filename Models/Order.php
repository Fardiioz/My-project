<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * Field yang dapat diisi massal
     * Status default 'pending', payment_method default 'cod' (cash on delivery)
     */
    protected $fillable = [
        'user_id',          // ID user yang membuat pesanan
        'total',            // Total harga pesanan
        'status',           // Status pesanan (pending, processing, shipped, delivered)
        'shipping_address', // Alamat pengiriman
        'payment_method',   // Metode pembayaran (cod, bank_transfer, etc.)
    ];

    /**
     * Casting tipe data otomatis
     * Total di-cast sebagai decimal dengan 2 digit desimal
     */
    protected $casts = [
        'total' => 'decimal:2',
    ];

    /**
     * Relationship: Satu order milik satu user
     * Menggunakan belongsTo karena order dimiliki oleh user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Satu order memiliki banyak order items
     * Menggunakan hasMany karena satu pesanan bisa memiliki banyak item
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
