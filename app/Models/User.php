<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Project;
use App\Models\Article;
use App\Models\InvitationCode;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'google_id',
        'kelas',
        'role',
        'jurusan',
        'bio',
        'instagram',
        'status',
        'invitation_code_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ====================== RELATIONSHIPS ======================

    public function invitationCode()
    {
        return $this->belongsTo(InvitationCode::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function reviewedProjects()
    {
        return $this->hasMany(Project::class, 'reviewed_by');
    }

    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    // ====================== JURUSAN ======================

    /** Samakan penulisan jurusan: huruf besar, tanpa spasi, TJKT = TKJ. */
    public static function normalizeJurusan(?string $jurusan): ?string
    {
        $key = strtoupper(trim((string) $jurusan));

        if ($key === 'TJKT') {
            $key = 'TKJ';
        }

        return $key === '' ? null : $key;
    }

    /** Semua penulisan jurusan yang dianggap sama (untuk whereIn). */
    public static function jurusanAliases(?string $jurusan): array
    {
        $key = static::normalizeJurusan($jurusan);

        return match ($key) {
            null    => [],
            'TKJ'   => ['TKJ', 'TJKT'],
            default => [$key],
        };
    }

    /** Siswa yang boleh dilihat admin: super admin = semua, admin jurusan = jurusannya saja. */
    public function scopeVisibleToAdmin($query, User $admin)
    {
        if ($admin->isSuperAdmin()) {
            return $query;
        }

        $aliases = static::jurusanAliases($admin->jurusan);

        return $aliases ? $query->whereIn('jurusan', $aliases) : $query->whereRaw('1 = 0');
    }

    /** Apakah $admin boleh mengelola siswa ini? */
    public function isManageableBy(User $admin): bool
    {
        if (!$this->isSiswa()) {
            return false;
        }

        if ($admin->isSuperAdmin()) {
            return true;
        }

        $key = static::normalizeJurusan($admin->jurusan);

        return $admin->isAdmin() && $key !== null && $key === static::normalizeJurusan($this->jurusan);
    }

    // ====================== HELPER METHODS ======================

    /**
     * Cek apakah Super Admin (role 0)
     */
    public function isSuperAdmin(): bool
    {
        return (int) $this->role === 0;
    }

    /**
     * Cek apakah Admin Jurusan (role 1)
     */
    public function isAdmin(): bool
    {
        return (int) $this->role === 1;
    }

    /**
     * Cek apakah Siswa (role 2)
     */
    public function isSiswa(): bool
    {
        return (int) $this->role === 2;
    }

    /**
     * Mengambil nama label role/jurusan secara dinamis
     */
    public function getRoleName(): string
    {
        if ($this->isSuperAdmin()) {
            return 'Super Admin';
        }

        if ($this->isAdmin()) {
            return 'Admin ' . ($this->jurusan ?? 'Jurusan');
        }

        return 'Siswa';
    }

    /**
     * Link wa.me dari nomor WhatsApp siswa, siap diklik.
     * Nomor dirapikan: hanya angka, awalan 0 diganti kode negara Indonesia (62).
     */
    public function getWhatsappUrlAttribute(): ?string
    {
        if (empty($this->phone_number)) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $this->phone_number);

        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        }

        return $digits !== '' ? 'https://wa.me/' . $digits : null;
    }
}