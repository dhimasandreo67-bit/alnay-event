{{-- $staf null = mode tambah, $staf terisi = mode edit --}}
<div class="form-grid">
    <div class="field">
        <label for="nama">Nama</label>
        <input type="text" id="nama" name="nama" value="{{ old('nama', $staf->nama ?? '') }}" required>
        @error('nama') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email', $staf->email ?? '') }}" required>
        @error('email') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    @if (!$staf)
        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required minlength="8">
            @error('password') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8">
        </div>
    @endif

    <div class="field">
        <label for="role">Role</label>
        <select id="role" name="role" required onchange="toggleRestrictedFields(this.value)">
            @foreach (\App\Models\AkunStaf::ROLES as $value => $label)
                <option value="{{ $value }}" {{ old('role', $staf->role ?? 'admin') === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('role') <span class="field-error">{{ $message }}</span> @enderror
        <p class="field-hint">Super Admin otomatis punya akses ke semua halaman &amp; semua event, tidak peduli pilihan di bawah.</p>
    </div>
</div>

<div id="restricted-fields" style="margin-top:1.5rem;">
    <div class="field-group">
        <h3>Halaman yang boleh diakses</h3>
        <div class="checkbox-grid">
            @foreach ($availablePages as $slug => $label)
                <label class="checkbox-item">
                    <input type="checkbox" name="allowed_pages[]" value="{{ $slug }}"
                        {{ in_array($slug, old('allowed_pages', $staf->allowed_pages ?? [])) ? 'checked' : '' }}>
                    {{ $label }}
                </label>
            @endforeach
        </div>
    </div>

    <div class="field-group">
        <h3>Event yang boleh diakses</h3>
        <div class="checkbox-grid">
            @forelse ($events as $event)
                <label class="checkbox-item">
                    <input type="checkbox" name="event_ids[]" value="{{ $event->id }}"
                        {{ in_array($event->id, old('event_ids', $selectedEventIds)) ? 'checked' : '' }}>
                    {{ $event->nama ?? $event->name ?? ('Event #' . $event->id) }}
                </label>
            @empty
                <p class="field-hint">Belum ada event.</p>
            @endforelse
        </div>
    </div>
</div>

<script>
    function toggleRestrictedFields(role) {
        const box = document.getElementById('restricted-fields');
        box.style.display = role === 'super_admin' ? 'none' : 'block';
    }
    document.addEventListener('DOMContentLoaded', function () {
        toggleRestrictedFields(document.getElementById('role').value);
    });
</script>
