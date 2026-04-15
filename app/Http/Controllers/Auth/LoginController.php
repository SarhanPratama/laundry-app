<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([             //proses validasi input email dan pass
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {              //laravel akan mencari user berdasarkan email dan password nya juga
            $request->session()->regenerate();          // session untuk membuat session id login yg baru, sedangkan regenerate Menghapus session ID lama

            return redirect()->route('dashboard');          //ketika login sukses, maka akan masuk halaman dashboard
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout(); //hapus status login user

        $request->session()->invalidate();      //Hapus semua session

        $request->session()->regenerateToken();

        return redirect()->route('login');      //Kembali ke halaman login
    }
}
