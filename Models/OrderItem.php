<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    /**
     * Field yang dapat diisi massal
     * Menyimpan detail item dalam satu pesanan
     */
    protected $fillable = [
        'order_id',   // ID pesanan yang berisi item ini
        'product_id', // ID produk yang dipesan
        'quantity',   // Jumlah produk yang dipesan
        'price',      // Harga produk saat dipesan (snapshot harga)
    ];

    /**
     * Casting tipe data otomatis
     * Price di-cast sebagai decimal dengan 2 digit desimal
     */
    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Relationship: Satu order item milik satu order
     * Menggunakan belongsTo karena order item adalah bagian dari order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relationship: Satu order item merujuk ke satu produk
     * Menggunakan belongsTo karena order item merujuk ke produk tertentu
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
