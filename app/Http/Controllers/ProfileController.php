<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{


    public function profile()
    {
        $user = Auth::user();

        return view('profile.index', compact('user'));
    }

    public function edit(Request $request): View
    {
        return view('admin.profile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();

        $user->name = $request->name;
        $user->email = $request->email;

        // cek jika user isi password baru
        if ($request->filled('new_password')) {
            // opsional: cek old_password cocok
            if (Hash::check($request->old_password, $user->password)) {
                $user->password = Hash::make($request->new_password);
            } else {
                return back()->withErrors(['old_password' => 'Password lama salah']);
            }
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,svg,heic|max:20480'
        ]);

        $user = $request->user();

        // Hapus foto lama jika ada
        if ($user->photo && file_exists(public_path($user->photo))) {
            unlink(public_path($user->photo));
        }

        // Buat nama file unik
        $filename = time() . '_' . $request->file('photo')->getClientOriginalName();

        // Pindahkan file ke public/img/upload/profile
        $request->file('photo')->move(public_path('img/upload/profile'), $filename);

        // Simpan path ke database (relative dari public)
        $user->photo = 'img/upload/profile/' . $filename;
        $user->save();

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    public function destroyPhoto(Request $request)
    {
        $user = $request->user();

        // hapus file lama jika ada
        if ($user->photo && file_exists(public_path($user->photo))) {
            unlink(public_path($user->photo));
        }

        $user->photo = null;
        $user->save();

        return back()->with('success', 'Foto profil berhasil dihapus.');
    }
}
