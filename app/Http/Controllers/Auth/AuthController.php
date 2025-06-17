<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else if ($user->role === 'guru') {
                return redirect()->route('guru.dashboard');
            } else if ($user->role === 'siswa') {
                return redirect()->route('siswa.dashboard');
            }
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        $user = User::where('username', $request->username)->first();

        if ($user) {
            if ($user->role == 'admin') {
                if (Auth::attempt($request->only('username', 'password'), $request->has('remember'))) {
                    return redirect()->route('admin.dashboard');
                } else {
                    return redirect()->back()->with('error', 'Username atau password salah');
                }
            } else {
                if ($user->password == $request->password) {
                    Auth::login($user, $request->has('remember'));
                    if ($user->role == 'guru') {
                        return redirect()->route('guru.dashboard');
                    } else {
                        return redirect()->route('siswa.dashboard');
                    }
                } else {
                    return redirect()->back()->with('error', 'Username atau password salah');
                }
            }
        }

        return redirect()->back()->with('error', 'Username atau password salah');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required',
            'password_confirmation' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $user = User::where('id', Auth::user()->id)->first();

        if ($request->old_password != $user->password) {
            return redirect()->back()->with('error', 'Password lama tidak cocok');
        }

        // Auth::logout();
        $user->password = $request->new_password;
        $user->save();

        return redirect()->back()->with('success', 'Password berhasil diubah');
    }
}
