<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    use HasFactory;
     protected $fillable = ['ibu_id', 'tanggal_pemeriksaan', 'usia_kehamilan', 'tekanan_darah', 'riwayat_penyakit'];

    public function ibu()
    {
        return $this->belongsTo(Ibu::class);
    }

    public function klasifikasi()
    {
        return $this->hasOne(Klasifikasi::class);
    }
}
