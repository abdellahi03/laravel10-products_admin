<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Log; // Add this at the top

class AuthController extends Controller
{
    public function register()
    {
        Log::info('Controller: ' . __METHOD__); // Add this
        return view('auth/register');
    }

    public function registerSave(Request $request)
    {
        Log::info('Controller: ' . __METHOD__); // Add this
        Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ])->validate();

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => 'Admin'
        ]);

        return redirect()->route('login');
    }

    public function login()
    {
        Log::info('Controller: ' . __METHOD__); // Add this
        return view('auth/login');
    }

    public function loginAction(Request $request)
    {
        Log::info('Controller: ' . __METHOD__); // Add this
        Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ])->validate();

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed')
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Log::info('Controller: ' . __METHOD__); // Add this
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        return redirect('/');
    }

    public function profile()
    {
        Log::info('Controller: ' . __METHOD__); // Add this
        return view('profile');
    }
}
