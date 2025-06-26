<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use Illuminate\Http\Request;

class PencarianController extends Controller
{
    public function index()
    {
        return view('pencarian.index');
    }

    public function cariByNik(Request $request)
    {
        $request->validate([
            'nik' => 'required|digits:16'
        ]);

        $alternatif = Alternatif::with(['biodata', 'desa'])
            ->whereHas('biodata', function($query) use ($request) {
                $query->where('nik', $request->nik);
            })
            ->first();

        if (!$alternatif) {
            return back()->with('error', 'Data penerima dengan NIK tersebut tidak ditemukan');
        }

        return view('pencarian.hasil', compact('alternatif'));
    }
}