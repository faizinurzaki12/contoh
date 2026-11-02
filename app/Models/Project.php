<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'jurusan',
        'file_path',
        'file_type',
        'file_size',
        'guru_pengampu',
        'live_link',
        'github_link',
        'technology_stack',
        'status',
        'rejection_reason',
        'approval_note',
        'reviewed_by',
        'reviewed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'reviewed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * RELATIONSHIPS
     */

    /**
     * Get user (siswa) yang upload project ini
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get category project ini
     */
   

    /**
     * Get admin yang review project ini
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * SCOPES
     */

    /**
     * Scope untuk ambil project yang pending (belum di-review)
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk ambil project yang approved
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope untuk ambil project yang rejected
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope untuk ambil project dari user tertentu
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope untuk ambil project dari jurusan tertentu
     */
    public function scopeByJurusan($query, $jurusan)
    {
        return $query->where('jurusan', $jurusan);
    }

    /**
     * Scope untuk ambil project dari guru tertentu
     */
    public function scopeByGuru($query, $guru)
    {
        return $query->where('guru_pengampu', $guru);
    }

    /**
     * Karya yang boleh dilihat admin: super admin = semua, admin jurusan = jurusannya saja.
     */
    public function scopeVisibleToAdmin($query, User $admin)
    {
        if ($admin->isSuperAdmin()) {
            return $query;
        }

        $aliases = User::jurusanAliases($admin->jurusan);

        return $aliases ? $query->whereIn('jurusan', $aliases) : $query->whereRaw('1 = 0');
    }

    /**
     * Apakah $admin boleh me-review / menghapus karya ini?
     */
    public function isManageableBy(User $admin): bool
    {
        if ($admin->isSuperAdmin()) {
            return true;
        }

        $key = User::normalizeJurusan($admin->jurusan);

        return $admin->isAdmin() && $key !== null && $key === User::normalizeJurusan($this->jurusan);
    }

    /**
     * HELPER METHODS
     */

    /**
     * Check apakah project sudah di-approve
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check apakah project masih pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check apakah project di-reject
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColor(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Review',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => 'Unknown',
        };
    }

    /**
     * Increment views count
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Toggle likes
     */
    public function toggleLike()
    {
        if ($this->likes_count > 0) {
            $this->decrement('likes_count');
        } else {
            $this->increment('likes_count');
        }
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(ProjectLike::class);
    }
}