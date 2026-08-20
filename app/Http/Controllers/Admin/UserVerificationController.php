<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;


class UserVerificationController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'owner')->orderBy('id', 'asc')->get();
        $businesses = Business::all();
        return view('admin.manage-user.verify-users', compact('users', 'businesses'));
    }

    public function addUser(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', Rules\Password::defaults()],
            'id_business' => ['required', 'exists:business,id'],
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'pegawai',
            'is_verified' => true,
            'password' => Hash::make($request->password),
            'id_business' => $request->id_business,
        ]);


        event(new Registered($user));


        return back()->with('success', 'Akun berhasil di tambahkan.');
    }

    public function verify(User $user)
    {
        $user->update(['is_verified' => true]);

        return back()->with('success', 'Akun berhasil di verifikasi.');
    }

    public function inverify(User $user)
    {
        $user->update(['is_verified' => false]);

        return back()->with('success', 'Akun berhasil di inverifikasi.');
    }

    public function deleteUser($id)
    {
        $employee = User::findOrFail($id);
        $employee->delete();

        return redirect()->route('admin.verify-users')->with('success', 'Pegawai berhasil dihapus');
    }

    public function showDetail($id)
    {
        $user = User::findOrFail($id);

        return view('admin.manage-user.detail', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user->update($request->only(['name', 'email']));

        return redirect()->route('admin.users.detail', $id)->with('success', 'Data pegawai berhasil diperbarui');
    }
}
