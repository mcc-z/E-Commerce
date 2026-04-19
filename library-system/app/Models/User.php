<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'address', 'avatar',
        'member_id', 'role', 'status', 'password',
        'outstanding_fines', 'total_borrowed',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'outstanding_fines' => 'decimal:2',
    ];

    // ==================== RELATIONSHIPS ====================

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    public function activeBorrows()
    {
        return $this->hasMany(Borrow::class)->whereIn('status', ['active', 'overdue']);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function activeReservations()
    {
        return $this->hasMany(Reservation::class)->whereIn('status', ['pending', 'ready']);
    }

    public function fines()
    {
        return $this->hasMany(Fine::class);
    }

    public function unpaidFines()
    {
        return $this->hasMany(Fine::class)->whereIn('status', ['unpaid', 'partial']);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeBlocked($query)
    {
        return $query->where('status', 'blocked');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeMembers($query)
    {
        return $query->where('role', 'user');
    }

    // ==================== HELPERS ====================

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isBlocked(): bool
    {
        return $this->status === 'blocked';
    }

    public function canBorrow(): bool
    {
        if ($this->isBlocked()) return false;
        if ($this->outstanding_fines > 10) return false;
        if ($this->activeBorrows()->count() >= config('library.max_books_per_user', 5)) return false;
        return true;
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            return route('avatars.show', ['filename' => basename($this->avatar)]);
        }

        $initials = Str::of($this->name)
            ->explode(' ')
            ->filter()
            ->map(fn (string $part) => Str::upper(Str::substr($part, 0, 1)))
            ->take(2)
            ->implode('');

        $svg = sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128"><rect width="128" height="128" rx="64" fill="#1e3a5f"/><text x="50%%" y="50%%" dy="0.35em" text-anchor="middle" font-family="Arial, sans-serif" font-size="44" font-weight="700" fill="#ffffff">%s</text></svg>',
            e($initials ?: 'U')
        );

        return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($svg);
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'active'    => ['label' => 'Active',     'class' => 'badge-success'],
            'blocked'   => ['label' => 'Blocked',    'class' => 'badge-danger'],
            'suspended' => ['label' => 'Suspended',  'class' => 'badge-warning'],
            default     => ['label' => 'Unknown',    'class' => 'badge-secondary'],
        };
    }

    // Auto-generate member ID
    public static function generateMemberId(): string
    {
        $year = date('Y');
        $last = self::withTrashed()->where('member_id', 'like', "LIB-{$year}-%")
                    ->orderByDesc('id')->first();
        $seq = $last ? (int) substr($last->member_id, -4) + 1 : 1;
        return "LIB-{$year}-" . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
