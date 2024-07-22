<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;
    protected $guarded = [
        'id'
    ];
    /**
     * Get all of the pesanan for the Pelanggan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class);
    }
    /**
     * Get all of the pesanan for the Pelanggan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function JarakGudang()
    {
        return $this->hasMany(JarakGudang::class, 'from_customer');
    }
    /**
     * Get all of the JarakPelanggan for the Pelanggan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function JarakPelanggan()
    {
        return $this->hasMany(JarakPelanggan::class, 'to_customer');
    }
}
