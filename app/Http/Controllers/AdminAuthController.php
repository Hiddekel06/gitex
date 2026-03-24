<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Useradmin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminAuthController extends Controller
{
    // Affiche le formulaire de connexion admin
    public function showLoginForm()
    {
        return view('admin.login');
    }

    // Traite la connexion admin
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Useradmin::where('email', $request->email)->first();
        if ($admin && Hash::check($request->password, $admin->password)) {
            // Authentification réussie, on stocke l'admin en session
            Session::put('admin_id', $admin->id);
            return redirect()->route('admin.dashboard');
        } else {
            return back()->with('error', 'Identifiants invalides.');
        }
    }
}
