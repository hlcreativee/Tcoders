<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required',
            'role' => 'required',
            'email' => 'required|email',
            'hp' => 'required',
            'alamat' => 'required',
            'foto' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $user->foto = $filename;
        }

        $user->name = $request->name;
        $user->role = $request->role;
        $user->status = $request->status;
        $user->email = $request->email;
        $user->hp = $request->hp;
        $user->alamat = $request->alamat;

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diupdate');
    }
}