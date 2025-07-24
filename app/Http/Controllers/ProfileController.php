<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('profile.index');
    }

    public function update(Request $request)
    {
        try {
            $user = Auth::user();
            
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'phone_number' => 'nullable|string|max:20',
                'current_password' => 'nullable|required_with:password',
                'password' => 'nullable|min:8|confirmed',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
            ], [
                'avatar.image' => 'File harus berupa gambar.',
                'avatar.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
                'avatar.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
            ]);

            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone_number = $request->phone_number;

            if ($request->hasFile('avatar')) {
                try {
                    // Delete old avatar
                    $user->deleteAvatar();

                    // Store new avatar
                    $avatar = $request->file('avatar');
                    $fileName = time() . '_' . $avatar->getClientOriginalName();
                    $path = $avatar->storeAs('avatars', $fileName, 'public');
                    
                    if (!$path) {
                        throw new \Exception('Gagal menyimpan gambar profil.');
                    }
                    
                    $user->avatar = $path;
                } catch (\Exception $e) {
                    return back()
                        ->withInput()
                        ->with('error', 'Gagal mengunggah gambar profil: ' . $e->getMessage());
                }
            }

        if ($request->password) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return back()
                        ->withInput()
                        ->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
                }
                $user->password = Hash::make($request->password);
            }

            if ($user->save()) {
                return back()->with('success', [
                    'message' => 'Profil berhasil diperbarui!',
                    'icon' => 'success'
                ]);
            } else {
                throw new \Exception('Gagal menyimpan perubahan profil.');
            }
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', [
                    'message' => 'Gagal memperbarui profil: ' . $e->getMessage(),
                    'icon' => 'error'
                ]);
        }
    }
}
