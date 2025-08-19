<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null)
    {
        $html = '
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
<div class="container" style="margin-top: 50px;">
    <h2>Reset Password</h2>
    
    <form method="POST" action="' . route('password.update') . '">
        ' . csrf_field() . '
        <input type="hidden" name="token" value="' . $token . '">
        
        <div class="form-group">
            <label>Email:</label>
            <input type="email" class="form-control" name="email" value="' . ($request->email ?? '') . '" required readonly>
        </div>
        
        <div class="form-group">
            <label>Password Baru:</label>
            <input type="password" class="form-control" name="password" required minlength="8" placeholder="Minimal 8 karakter">
        </div>
        
        <div class="form-group">
            <label>Konfirmasi Password:</label>
            <input type="password" class="form-control" name="password_confirmation" required minlength="8" placeholder="Ulangi password baru">
        </div>
        
        <button type="submit" class="btn btn-primary">Reset Password</button>
    </form>
</div>
</body>
</html>';

        return response($html);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Password berhasil direset! Silakan login dengan password baru.')
            : back()->withErrors(['email' => 'Token tidak valid atau sudah kadaluarsa.']);
    }
}