<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JarakPelanggan extends Model
{
    use HasFactory;
    protected $guarded = [
        'id'
    ];
    /**
     * Get the pelanggan that owns the JarakPelanggan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'from_customer');
    }
}
