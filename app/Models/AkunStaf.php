<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class AkunStaf extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'akun_staf';

    // Tabel ini tidak punya created_at/updated_at, adanya 'dibuat_pada'
    public $timestamps = false;

    /**
     * Daftar halaman admin yang bisa dibatasi aksesnya untuk role 'admin'.
     * Key = slug yang dipakai di parameter middleware ('admin.page:<slug>')
     * dan juga dicocokkan dengan variabel $active di sidebar.
     */
    public const AVAILABLE_PAGES = [
        'dashboard'       => 'Verifikasi Pendaftaran Peserta',
        'form-builder'    => 'Form Pendaftaran Event',
        'events-index'    => 'Kelola Event',
        'activation'      => 'Aktivasi Akun Peserta',
        'peserta-tampil'  => 'Kelola Peserta Tampil',
        'voting-settings' => 'Pengaturan Event & Voting',
        'voters'          => 'Daftar Voter',
        'leaderboard'     => 'Leaderboard Suara Voting',
    ];

    public const ROLES = [
        'super_admin' => 'Super Admin',
        'admin'       => 'Admin',
    ];

    protected $fillable = [
        'nama',
        'email',
        'password_hash',
        'last_active_event_id',
        'role',
        'allowed_pages',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $casts = [
        'password_hash' => 'hashed',
        'dibuat_pada' => 'datetime',
        'allowed_pages' => 'array',
    ];

    /**
     * Laravel's Authenticatable secara default cari kolom 'password'.
     * Kita override supaya pakai 'password_hash' sesuai kolom asli.
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function lastActiveEvent()
    {
        return $this->belongsTo(Event::class, 'last_active_event_id');
    }

    /**
     * Event-event yang boleh diakses admin ini (tidak berlaku untuk super_admin,
     * karena super_admin selalu bisa akses semua event — lihat hasEventAccess()).
     */
    public function allowedEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'akun_staf_event', 'akun_staf_id', 'event_id')
            ->withTimestamps();
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Cek apakah admin ini boleh membuka halaman dengan slug tertentu.
     * Super admin selalu diizinkan.
     */
    public function hasPageAccess(string $page): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return in_array($page, $this->allowed_pages ?? [], true);
    }

    /**
     * Cek apakah admin ini boleh mengakses event tertentu.
     * Super admin selalu diizinkan. $eventId null dianggap "tidak relevan" (diizinkan),
     * supaya halaman yang tidak terikat event tertentu tidak ikut diblokir.
     */
    public function hasEventAccess(?int $eventId): bool
    {
        if (! $eventId || $this->isSuperAdmin()) {
            return true;
        }

        return $this->allowedEvents()->where('event.id', $eventId)->exists();
    }
}