<?php

namespace App\Http\Controllers;


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User; // Penting untuk User::create
use App\Providers\RouteServiceProvider; // Penting untuk RouteServiceProvider::HOME
use Illuminate\Auth\Events\Registered; // Penting untuk new Registered($user)
use Illuminate\Http\RedirectResponse; // Penting untuk return type RedirectResponse
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Penting untuk Auth::login
use Illuminate\Support\Facades\Hash; // Penting untuk Hash::make
use Illuminate\Validation\Rules; // Penting untuk Rules\Password

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'role' => ['required', 'string', 'in:admin,staff'], // Tambahkan ini
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role, // Tambahkan ini
    ]);

    event(new Registered($user));

    Auth::login($user);

    return redirect('/dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
