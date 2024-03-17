<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
    use HasFactory;
    protected $fillable = ['merek', 'model', 'nomor_plat', 'tarif_sewa'];
    public function rental()
    {
        return $this->hasOne(Rental::class, 'id_mobil');
    }

}
