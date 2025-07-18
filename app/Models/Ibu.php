<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ibu extends Model
{
    use HasFactory;
    protected $fillable = ['nama', 'tanggal_lahir', 'alamat'];

    public function pemeriksaans()
    {
        return $this->hasMany(Pemeriksaan::class);
    }
}
