<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller // Pastikan ini meng-extend Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // Anda bisa ganti RouteServiceProvider::HOME dengan '/admin/dashboard'
    // jika Anda ingin admin langsung ke dashboard setelah login.
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = '/admin/dashboard';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        // Baris ini tidak wajib jika logout sudah dilindungi di rute,
        // tapi tidak menyebabkan masalah jika LoginController meng-extend Controller dengan benar.
        // $this->middleware('auth')->only('logout');
    }
}
