<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AccountController extends Controller
{
    /**
     * PBI-04 dan PBI-05: daftar akun, dengan pencarian dan filter.
     */
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', Rule::in(array_keys(User::ROLE_LABELS))],
            'status' => ['nullable', Rule::in(array_keys(User::STATUS_LABELS))],
        ]);

        $accounts = User::query()
            ->when($filters['q'] ?? null, function ($query, $term) {
                $like = '%'.addcslashes($term, '%_\\').'%';

                $query->where(fn ($q) => $q->where('name', 'like', $like)->orWhere('email', 'like', $like));
            })
            ->when($filters['role'] ?? null, fn ($query, $role) => $query->where('role', $role))
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('verification_status', $status))
            ->orderByDesc('created_at')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.accounts.index', [
            'accounts' => $accounts,
            'filters' => $filters,
            'hasFilter' => collect($filters)->filter()->isNotEmpty(),
        ]);
    }

    /**
     * PBI-04: detail akun.
     */
    public function show(User $user): View
    {
        return view('admin.accounts.show', ['account' => $user]);
    }
}