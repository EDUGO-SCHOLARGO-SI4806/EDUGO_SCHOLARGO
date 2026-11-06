<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScholarshipRequest;
use App\Http\Requests\UpdateScholarshipRequest;
use App\Models\Beasiswa;
use Illuminate\Support\Facades\Storage;

class ScholarshipController extends Controller
{
    public function index()
    {
        $scholarships = Beasiswa::latest()->paginate(10);

        return view('admin.scholarships.index', compact('scholarships'));
    }

    public function show(Beasiswa $scholarship)
    {
        return view('admin.scholarships.show', compact('scholarship'));
    }

    public function create()
    {
        $recentScholarships = Beasiswa::latest()->take(5)->get();
        return view('admin.scholarships.create', compact('recentScholarships'));
    }

    public function store(StoreScholarshipRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        Beasiswa::create($data);

        return redirect()
            ->route('admin.scholarships.index')
            ->with('success', 'Beasiswa berhasil ditambahkan.');
    }

    public function edit(Beasiswa $scholarship)
    {
        $recentScholarships = Beasiswa::latest()->take(5)->get();
        return view('admin.scholarships.edit', compact('scholarship', 'recentScholarships'));
    }

    public function update(UpdateScholarshipRequest $request, Beasiswa $scholarship)
    {
        $data = $request->validated();

        // Hapus poster lama jika user centang "Hapus Poster" atau upload poster baru
        if ($request->boolean('hapus_poster') || $request->hasFile('poster')) {
            if ($scholarship->poster) {
                Storage::disk('public')->delete($scholarship->poster);
            }
            $data['poster'] = null;
        }

        // Simpan poster baru jika ada
        if ($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $scholarship->update($data);

        return redirect()
            ->route('admin.scholarships.show', $scholarship)
            ->with('success', 'Data beasiswa berhasil diperbarui.');
    }

    public function destroy(Beasiswa $scholarship)
    {
        $scholarship->delete(); // Soft delete — data tetap ada di DB, deleted_at terisi

        return redirect()
            ->route('admin.scholarships.index')
            ->with('success', 'Beasiswa berhasil dihapus.');
    }
}
