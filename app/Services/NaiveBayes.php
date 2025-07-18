<?php
namespace App\Services;

class NaiveBayes
{
    public static function klasifikasi($ibu)
    {
        // Contoh sederhana
        $nilai = 0;

        if ($ibu->hamil_berisiko) $nilai += 1;
        if ($ibu->tekanan_darah > 140) $nilai += 1;
        if ($ibu->gula_darah > 200) $nilai += 1;

        if ($nilai == 0) return ['label' => 'Rendah', 'warna' => 'success'];
        if ($nilai == 1) return ['label' => 'Sedang', 'warna' => 'warning'];
        return ['label' => 'Tinggi', 'warna' => 'danger'];
    }
}
