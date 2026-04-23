<?php

use App\Http\Controllers\ReporteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/contacto', function () {
    return view('contacto');
})->name('contact');

// --- LOGIN ---
Route::get('/auth', function () {
    return view('auth.login');
})->name('login.show');

Route::post('/auth', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect('/reportes');
    }

    return back()->withErrors([
        'email' => 'Las credenciales no coinciden con nuestros registros.',
    ])->withInput();
})->name('login');

// --- REGISTRO ---
Route::get('/auth/sign_up', function () {
    return view('auth.sign_up');
})->name('register.show');

Route::post('/auth/sign_up', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
    ]);

    Auth::login($user);

    return redirect('/reportes');
})->name('register');

// --- LOGOUT ---
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// --- RUTAS PROTEGIDAS ---
Route::middleware('auth')->group(function () {
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::post('/reportes', [ReporteController::class, 'store'])->name('reportes.store');
    Route::delete('/reportes/{reporte}', [ReporteController::class, 'destroy'])->name('reportes.destroy');
});

Route::get('/reportes/{reporte}', [ReporteController::class, 'show'])
     ->name('reportes.show');
     
Route::patch('/reportes/{reporte}/estado', [ReporteController::class, 'updateEstado'])
     ->name('reportes.updateEstado');