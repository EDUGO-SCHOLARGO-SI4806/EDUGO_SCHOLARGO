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
        $trashedCount = Beasiswa::onlyTrashed()->count();

        return view('admin.scholarships.index', compact('scholarships', 'trashedCount'));
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
            ->with('success', 'Beasiswa "' . $scholarship->nama . '" berhasil dipindahkan ke arsip beasiswa.');
    }

    /**
     * Halaman daftar beasiswa yang diarsipkan (soft deleted).
     */
    public function trashed()
    {
        $scholarships = Beasiswa::onlyTrashed()->latest('deleted_at')->paginate(10);

        return view('admin.scholarships.trashed', compact('scholarships'));
    }

    /**
     * Memulihkan beasiswa dari tempat sampah (restore).
     */
    public function restore($id)
    {
        $scholarship = Beasiswa::onlyTrashed()->findOrFail($id);
        $scholarship->restore();

        return redirect()
            ->route('admin.scholarships.trashed')
            ->with('success', 'Beasiswa "' . $scholarship->nama . '" berhasil dipulihkan dari arsip!');
    }

    /**
     * Menghapus beasiswa secara permanen dari database dan membersihkan poster fisik.
     */
    public function forceDelete($id)
    {
        $scholarship = Beasiswa::onlyTrashed()->findOrFail($id);

        // Hapus file fisik poster dari storage jika ada
        if ($scholarship->poster) {
            Storage::disk('public')->delete($scholarship->poster);
        }

        $nama = $scholarship->nama;
        $scholarship->forceDelete();

        return redirect()
            ->route('admin.scholarships.trashed')
            ->with('success', 'Beasiswa "' . $nama . '" telah dihapus permanen dari sistem.');
    }
}
