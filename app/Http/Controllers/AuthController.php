<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $loginField = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        $loginData = [
            $loginField => $credentials['login'],
            'password' => $credentials['password'],
        ];

        if (Auth::attempt($loginData, $request->boolean('remember'))) {
            // Redirect ke dashboard user setelah login berhasil
            return redirect()->route('dashboard')->with('success', 'Login berhasil!');
        }

        return back()->withErrors(['login' => 'Email/Username atau password salah.']);
    }

    // Method untuk menampilkan dashboard user
    public function dashboard()
    {
        $user = Auth::user();
        return view('dashboard', compact('user'));
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'username' => 'required|unique:users',
            'password' => 'required|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $profilePhoto = null;
        if ($request->hasFile('profile_photo')) {
            $profilePhoto = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'profile_photo' => $profilePhoto,
            'role' => 'user',
        ]);

        // Log untuk debugging
        Log::info('User created:', ['user_id' => $user->id, 'profile_photo' => $profilePhoto]);

        // Auto login setelah registrasi agar bisa upload foto
        Auth::login($user);

        // Redirect ke halaman success dengan data user
        return view('auth.register-success', compact('user'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('home');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users']);

        Password::sendResetLink($request->only('email'));
        return back()->with('status', 'Link reset dikirim ke email!');
    }

    public function showResetForm($token)
    {
        return view('auth.passwords.reset', compact('token'));
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Password berhasil direset!')
            : back()->withErrors(['email' => 'Reset password gagal']);
    }

    public function uploadPhoto(Request $request)
    {
        try {
            // Log semua data yang masuk untuk debugging
            Log::info('=== UPLOAD PHOTO REQUEST START ===');
            Log::info('Request method:', ['method' => $request->method()]);
            Log::info('Request all data:', $request->all());
            Log::info('Request files:', ['files' => $request->file()]);
            Log::info('Has profile_photo file:', ['has_file' => $request->hasFile('profile_photo')]);
            
            // Validasi input
            $request->validate([
                'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'user_id' => 'required|exists:users,id'
            ]);

            Log::info('Validation passed');

            // Cari user
            $user = User::find($request->user_id);
            
            if (!$user) {
                Log::error('User not found:', ['user_id' => $request->user_id]);
                return response()->json([
                    'success' => false, 
                    'message' => 'User tidak ditemukan!'
                ], 404);
            }

            Log::info('User found:', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'current_photo' => $user->profile_photo
            ]);

            // Hapus foto lama jika ada
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                $deleted = Storage::disk('public')->delete($user->profile_photo);
                Log::info('Old photo deletion:', [
                    'old_photo' => $user->profile_photo,
                    'deleted' => $deleted
                ]);
            }
            
            // Upload foto baru
            $uploadedFile = $request->file('profile_photo');
            Log::info('File info:', [
                'original_name' => $uploadedFile->getClientOriginalName(),
                'size' => $uploadedFile->getSize(),
                'mime_type' => $uploadedFile->getMimeType()
            ]);
            
            $profilePhoto = $uploadedFile->store('profile_photos', 'public');
            Log::info('File stored:', ['path' => $profilePhoto]);
            
            // Verifikasi file tersimpan
            $fileExists = Storage::disk('public')->exists($profilePhoto);
            Log::info('File exists after upload:', ['exists' => $fileExists]);
            
            // Update database
            $oldPhotoValue = $user->profile_photo;
            $user->profile_photo = $profilePhoto;
            $saveResult = $user->save();
            
            Log::info('Database update:', [
                'old_value' => $oldPhotoValue,
                'new_value' => $profilePhoto,
                'save_result' => $saveResult,
                'user_id' => $user->id
            ]);
            
            // Verifikasi update berhasil
            $user->refresh(); // Reload dari database
            Log::info('User after refresh:', [
                'user_id' => $user->id,
                'profile_photo_in_db' => $user->profile_photo
            ]);
            
            $photoUrl = asset('storage/' . $profilePhoto);
            Log::info('=== UPLOAD PHOTO SUCCESS ===', [
                'photo_path' => $profilePhoto,
                'photo_url' => $photoUrl
            ]);
            
            return response()->json([
                'success' => true, 
                'message' => 'Foto berhasil diupload dan disimpan ke database!',
                'photo_url' => $photoUrl,
                'photo_path' => $profilePhoto,
                'user_id' => $user->id
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', [
                'errors' => $e->validator->errors()->all(),
                'input' => $request->all()
            ]);
            return response()->json([
                'success' => false, 
                'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('=== UPLOAD PHOTO ERROR ===', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Gagal upload foto: ' . $e->getMessage()
            ], 500);
        }
    }
}