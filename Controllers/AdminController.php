<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminController extends Controller
{
    /**
     * Tampilkan form login admin
     */
    public function showLoginForm()
    {
        return view('admin.login');
    }

    /**
     * Proses login admin
     */
    public function login(Request $request)
    {
        $request->validate([
            'Email' => 'required|email',
            'Password' => 'required|string',
        ]);

        $credentials = [
            'Email' => $request->Email,
            'password' => $request->Password,
        ];

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors([
            'Email' => 'Email atau password salah.',
        ])->onlyInput('Email');
    }

    /**
     * Logout admin
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }

    /**
     * Dashboard admin
     */
    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.dashboard', compact('admin'));
    }

    // API Methods untuk integrasi dengan Nuxt.js

    /**
     * API: Login admin
     * POST /api/admin/login
     */
    public function apiLogin(Request $request)
    {
        $request->validate([
            'Email' => 'required|email',
            'Password' => 'required|string',
        ]);

        $admin = Admin::where('Email', $request->Email)->first();

        if ($admin && Hash::check($request->Password, $admin->Password)) {
            $token = $admin->createToken('admin-token')->plainTextToken;

            return response()->json([
                'admin' => $admin,
                'token' => $token,
                'message' => 'Login berhasil!'
            ]);
        }

        return response()->json([
            'message' => 'Email atau password salah.'
        ], 401);
    }

    /**
     * API: Dashboard admin
     * GET /api/admin/dashboard
     */
    public function apiDashboard(Request $request)
    {
        $admin = $request->user();
        return response()->json([
            'admin' => $admin,
            'message' => 'Dashboard data berhasil diambil!'
        ]);
    }

    /**
     * API: Logout admin
     * POST /api/admin/logout
     */
    public function apiLogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil!'
        ]);
    }
}
