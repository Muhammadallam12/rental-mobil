<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = ['id_pengguna', 'id_mobil', 'tanggal_mulai', 'tanggal_selesai','status'];
}
