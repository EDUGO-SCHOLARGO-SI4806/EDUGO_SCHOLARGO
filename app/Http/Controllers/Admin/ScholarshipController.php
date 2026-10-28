<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScholarshipRequest;
use App\Models\Scholarship;

class ScholarshipController extends Controller
{
    // Tambahkan method ini untuk menampilkan daftar beasiswa:
    public function index()
    {
        $scholarships = Scholarship::latest()->paginate(10); // atau Scholarship::all();
        return view('admin.scholarships.index', compact('scholarships'));
    }

    public function create()
    {
        $recentScholarships = Scholarship::latest()->take(5)->get();
        return view('admin.scholarships.create', compact('recentScholarships'));
    }

    public function store(StoreScholarshipRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        Scholarship::create($data);

        return redirect()
            ->route('admin.scholarships.create')
            ->with('success', 'Beasiswa berhasil ditambahkan.');
    }
}