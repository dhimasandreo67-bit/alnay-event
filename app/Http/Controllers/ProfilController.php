<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function update(Request $request)
    {
        $request->merge(['username' => strtolower(trim($request->username))]);

        $data = $request->validate([
    'username' => ['required', 'regex:/^[a-z0-9_]{3,20}$/', 'unique:users,username,' . $request->user()->id],
], [
    'username.regex'  => 'Gunakan 3–20 huruf kecil, angka, atau _',
    'username.unique' => 'Username ini sudah dipakai',
]);

$request->user()->update($data);

        return back()->with('status', 'Perubahan tersimpan');
    }

    public function updateFoto(Request $request)
    {
        $request->validate(['foto' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048']]);

        $user = $request->user();
        if ($user->foto) Storage::disk('public')->delete($user->foto);

        $user->update(['foto' => $request->file('foto')->store('foto-profil', 'public')]);

        return back()->with('status', 'Foto profil diperbarui');
    }

    public function hapusFoto(Request $request)
{
    $user = $request->user();
    if ($user->foto) Storage::disk('public')->delete($user->foto);
    $user->update(['foto' => null]);

    return back()->with('status', 'Foto profil dihapus');
}
}