<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AkunStaf;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StafController extends Controller
{
    /**
     * GET /admin/staf
     */
    public function index(): View
    {
        $stafList = AkunStaf::withCount('allowedEvents')
            ->orderByRaw("role = 'super_admin' desc")
            ->orderBy('nama')
            ->get();

        return view('admin.staf.index', compact('stafList'));
    }

    /**
     * GET /admin/staf/tambah
     */
    public function create(): View
    {
        return view('admin.staf.create', [
            'staf' => null,
            'events' => Event::orderByDesc('id')->get(),
            'selectedEventIds' => [],
            'availablePages' => AkunStaf::AVAILABLE_PAGES,
        ]);
    }

    /**
     * POST /admin/staf
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateStaf($request, isUpdate: false);

        $staf = AkunStaf::create([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'password_hash' => Hash::make($data['password']),
            'role' => $data['role'],
            'allowed_pages' => $data['role'] === 'super_admin' ? null : ($data['allowed_pages'] ?? []),
        ]);

        if ($data['role'] !== 'super_admin') {
            $staf->allowedEvents()->sync($data['event_ids'] ?? []);
        }

        return redirect()->route('admin.staf.index')
            ->with('success', "Akun admin \"{$staf->nama}\" berhasil dibuat.");
    }

    /**
     * GET /admin/staf/{staf}/edit
     */
    public function edit(AkunStaf $staf): View
    {
        return view('admin.staf.edit', [
            'staf' => $staf,
            'events' => Event::orderByDesc('id')->get(),
            'selectedEventIds' => $staf->allowedEvents()->pluck('event.id')->all(),
            'availablePages' => AkunStaf::AVAILABLE_PAGES,
        ]);
    }

    /**
     * PUT /admin/staf/{staf}
     */
    public function update(Request $request, AkunStaf $staf): RedirectResponse
    {
        $data = $this->validateStaf($request, isUpdate: true, stafId: $staf->id);

        // Jangan sampai Super Admin terakhir diturunkan jadi admin biasa — nanti tidak ada yang bisa kelola admin lagi
        if ($staf->role === 'super_admin' && $data['role'] !== 'super_admin') {
            $jumlahSuperAdmin = AkunStaf::where('role', 'super_admin')->count();
            if ($jumlahSuperAdmin <= 1) {
                throw ValidationException::withMessages([
                    'role' => 'Tidak bisa menurunkan role ini — minimal harus ada 1 Super Admin aktif.',
                ]);
            }
        }

        $staf->update([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'role' => $data['role'],
            'allowed_pages' => $data['role'] === 'super_admin' ? null : ($data['allowed_pages'] ?? []),
        ]);

        $staf->allowedEvents()->sync($data['role'] === 'super_admin' ? [] : ($data['event_ids'] ?? []));

        // Kalau yang diedit adalah admin yang sedang login, sinkronkan nama/email di session juga
        if ($staf->id === (int) $request->session()->get('admin_id')) {
            $request->session()->put('admin_name', $staf->nama);
            $request->session()->put('admin_email', $staf->email);
        }

        return redirect()->route('admin.staf.index')
            ->with('success', "Data admin \"{$staf->nama}\" berhasil diperbarui.");
    }

    /**
     * POST /admin/staf/{staf}/password
     */
    public function resetPassword(Request $request, AkunStaf $staf): RedirectResponse
    {
        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $staf->update(['password_hash' => Hash::make($data['password'])]);

        return redirect()->route('admin.staf.index')
            ->with('success', "Password admin \"{$staf->nama}\" berhasil diganti.");
    }

    /**
     * DELETE /admin/staf/{staf}
     */
    public function destroy(Request $request, AkunStaf $staf): RedirectResponse
    {
        if ($staf->id === (int) $request->session()->get('admin_id')) {
            return back()->with('error', 'Kamu tidak bisa menghapus akunmu sendiri.');
        }

        if ($staf->role === 'super_admin' && AkunStaf::where('role', 'super_admin')->count() <= 1) {
            return back()->with('error', 'Tidak bisa menghapus Super Admin terakhir.');
        }

        $nama = $staf->nama;
        $staf->delete();

        return redirect()->route('admin.staf.index')
            ->with('success', "Akun admin \"{$nama}\" berhasil dihapus.");
    }

    private function validateStaf(Request $request, bool $isUpdate, ?int $stafId = null): array
    {
        $rules = [
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('akun_staf', 'email')->ignore($stafId)],
            'role' => ['required', Rule::in(array_keys(AkunStaf::ROLES))],
            'allowed_pages' => ['nullable', 'array'],
            'allowed_pages.*' => [Rule::in(array_keys(AkunStaf::AVAILABLE_PAGES))],
            // NOTE: tabel event diasumsikan bernama 'event' (lihat migration akun_staf_event)
            'event_ids' => ['nullable', 'array'],
            'event_ids.*' => ['integer', Rule::exists('event', 'id')],
        ];

        if (! $isUpdate) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        return $request->validate($rules);
    }
}
