<?php

namespace App\Http\Controllers;

use App\Models\Ibu;
use Illuminate\Http\Request;

class NaiveBayesController extends Controller
{
    public function index()
{
    $dataIbu = Ibu::all(); // Atau sesuai input data
    return view('naive_bayes.index', compact('dataIbu'));
}
}
