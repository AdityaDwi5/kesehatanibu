<?php

namespace Database\Seeders;

use App\Models\Ibu;
use App\Models\Pemeriksaan;
use App\Models\Klasifikasi;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PemeriksaanSeeder extends Seeder
{
    public function run(): void
    {
        // Buat ibu dummy
        $ibu = Ibu::firstOrCreate([
            'nama' => 'Ibu Dummy',
            'tanggal_lahir' => '1990-01-01',
            'alamat' => 'Jl. Contoh No. 123'
        ]);

        $dataSet = [
            [30, 110, 'no', 'Rendah'],
            [36, 145, 'yes', 'Tinggi'],
            [32, 120, 'no', 'Sedang'],
            [34, 130, 'yes', 'Sedang'],
            [38, 150, 'yes', 'Tinggi'],
            [28, 105, 'no', 'Rendah'],
            [35, 140, 'no', 'Sedang'],
            [37, 155, 'yes', 'Tinggi'],
            [31, 125, 'no', 'Sedang'],
            [29, 115, 'no', 'Rendah'],
        ];

        foreach ($dataSet as [$usia, $tekanan, $penyakit, $hasil]) {
            $p = Pemeriksaan::create([
                'ibu_id' => $ibu->id,
                'tanggal_pemeriksaan' => Carbon::now()->subDays(rand(1, 100)),
                'usia_kehamilan' => $usia,
                'tekanan_darah' => $tekanan,
                'riwayat_penyakit' => $penyakit,
            ]);

            Klasifikasi::create([
                'pemeriksaan_id' => $p->id,
                'hasil' => $hasil,
            ]);
        }
    }
}
