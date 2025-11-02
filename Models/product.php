<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * Nama tabel yang digunakan model ini
     */
    protected $table = 'produk';

    /**
     * Primary key tabel
     */
    protected $primaryKey = 'id_produk';

    /**
     * Disable timestamps
     */
    public $timestamps = false;

    /**
     * Field yang dapat diisi massal (mass assignment)
     * Melindungi dari mass assignment vulnerability
     */
    protected $fillable = [
        'nama_produk',   // Nama produk
        'harga_produk',  // Harga produk
        'jenis_produk',  // Jenis produk
    ];

    /**
     * Casting tipe data otomatis
     * Mengubah kolom harga_produk menjadi tipe integer
     */
    protected $casts = [
        'harga_produk' => 'integer',
    ];

    /**
     * Relationship: Satu produk bisa ada di banyak order item
     * Menggunakan hasMany karena satu produk bisa dipesan berkali-kali
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
