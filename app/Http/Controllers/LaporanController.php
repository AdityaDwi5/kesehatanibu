<?php

namespace App\Http\Controllers;

use App\Models\Klasifikasi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class LaporanController extends Controller
{
    public function klasifikasiBulanan()
    {
        $data = Klasifikasi::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as bulan"),
                DB::raw("SUM(CASE WHEN hasil = 'Rendah' THEN 1 ELSE 0 END) as rendah"),
                DB::raw("SUM(CASE WHEN hasil = 'Sedang' THEN 1 ELSE 0 END) as sedang"),
                DB::raw("SUM(CASE WHEN hasil = 'Tinggi' THEN 1 ELSE 0 END) as tinggi")
            )
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get();

        return view('laporan.klasifikasi', compact('data'));
    }
}
