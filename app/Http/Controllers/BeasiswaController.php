<?php

namespace App\Http\Controllers;

use App\Models\Beasiswa;
use Illuminate\Http\Request;

class BeasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Beasiswa::aktif()->filter($request->only(['q', 'batas_waktu', 'jenjang', 'pendanaan']));

        match ($request->query('urutan')) {
            'terbaru'  => $query->latest(),
            'deadline' => $query->orderBy('tanggal_tutup'),
            default    => $query->orderBy('id'),
        };

        $beasiswas = $query->paginate(12)->withQueryString();

        return view('beasiswa.index', compact('beasiswas'));
    }
}