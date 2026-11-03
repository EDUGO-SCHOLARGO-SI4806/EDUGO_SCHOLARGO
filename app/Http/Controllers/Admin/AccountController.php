<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View
    {
        $accounts = User::query()
            ->latest()
            ->paginate(10);

        return view('admin.accounts.index', compact('accounts'));
    }

    public function show(User $user): View
    {
        return view('admin.accounts.show', ['account' => $user]);
    }
}