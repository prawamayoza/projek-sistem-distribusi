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
}
