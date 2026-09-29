<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AkunStaf;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * GET /admin/profile
     */
    public function edit(Request $request): View
    {
        $admin = AkunStaf::findOrFail($request->session()->get('admin_id'));

        return view('admin.profile.edit', compact('admin'));
    }

    /**
     * POST /admin/profile
     */
    public function update(Request $request): RedirectResponse
    {
        $admin = AkunStaf::findOrFail($request->session()->get('admin_id'));

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('akun_staf', 'email')->ignore($admin->id)],
        ]);

        $admin->update($data);

        // Ikutan update nama yang tampil di session (dipakai partial sidebar)
        $request->session()->put('admin_name', $admin->nama);
        $request->session()->put('admin_email', $admin->email);

        return redirect()->route('admin.profile')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * POST /admin/profile/password
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $admin = AkunStaf::findOrFail($request->session()->get('admin_id'));

        $data = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($data['current_password'], $admin->password_hash)) {
            throw ValidationException::withMessages([
                'current_password' => 'Password lama yang kamu masukkan salah.',
            ]);
        }

        $admin->update(['password_hash' => Hash::make($data['password'])]);

        return redirect()->route('admin.profile')->with('success', 'Password berhasil diubah.');
    }
}