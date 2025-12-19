<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    /**
     * Nama tabel yang digunakan model ini
     */
    protected $table = 'admin';

    /**
     * Primary key tabel
     */
    protected $primaryKey = 'id_admin';

    /**
     * Field yang dapat diisi massal (mass assignment)
     */
    protected $fillable = [
        'nama_admin',
        'Tingkat_admin',
        'Email',
        'Password',
    ];

    /**
     * Field yang harus disembunyikan saat serialisasi
     */
    protected $hidden = [
        'Password',
    ];

    /**
     * Casting tipe data otomatis
     */
    protected $casts = [
        'Password' => 'hashed',
    ];

    /**
     * Get the password for authentication
     */
    public function getAuthPassword()
    {
        return $this->Password;
    }

    /**
     * Get the column name for the password.
     */
    public function getAuthPasswordName()
    {
        return 'Password';
    }

    /**
     * Get the column name for the "remember me" token.
     */
    public function getRememberTokenName()
    {
        return null; // Disable remember token for admin
    }

    /**
     * Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifierName()
    {
        return 'id_admin';
    }

    /**
     * Get the unique identifier for the user.
     */
    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }
}
