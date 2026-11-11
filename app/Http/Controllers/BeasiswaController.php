<?php

namespace App\Http\Controllers;

use App\Models\Beasiswa;
use Illuminate\Http\Request;

class BeasiswaController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->query('q', ''));

        $query = Beasiswa::aktif()->filter($request->only(['q', 'batas_waktu', 'jenjang', 'pendanaan']));

        switch ($request->query('urutan')) {
            case 'terbaru':
                $query->latest();
                break;
            case 'deadline':
                $query->orderBy('tanggal_tutup');
                break;
            default:
                if ($keyword !== '') {
                    $query->orderByRaw(
                        'CASE WHEN nama LIKE ? THEN 0 ELSE 1 END',
                        ['%' . addcslashes($keyword, '%_\\') . '%']
                    );
                }
                $query->orderBy('id');
        }

        $beasiswas = $query->paginate(12)->withQueryString();
        return view('beasiswa.index', compact('beasiswas', 'keyword'));
    }
}