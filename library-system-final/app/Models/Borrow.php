<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrow_code', 'user_id', 'book_id', 'borrowed_at', 'due_date',
        'returned_at', 'status', 'issued_by', 'returned_to', 'notes', 'renewal_count',
    ];

    protected $casts = [
        'borrowed_at' => 'date',
        'due_date'    => 'date',
        'returned_at' => 'date',
    ];

    // ==================== RELATIONSHIPS ====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function issuedByAdmin()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function returnedToAdmin()
    {
        return $this->belongsTo(User::class, 'returned_to');
    }

    public function fine()
    {
        return $this->hasOne(Fine::class);
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
                     ->orWhere(function ($q) {
                         $q->where('status', 'active')
                           ->where('due_date', '<', now());
                     });
    }

    // ==================== HELPERS ====================

    public function isOverdue(): bool
    {
        return $this->status === 'overdue' ||
               ($this->status === 'active' && $this->due_date < now()->startOfDay());
    }

    public function getOverdueDaysAttribute(): int
    {
        if (!$this->isOverdue()) return 0;
        $compareDate = $this->returned_at ?? now();
        return max(0, $this->due_date->diffInDays($compareDate));
    }

    public function calculateFine(): float
    {
        if (!$this->isOverdue()) return 0;
        return $this->overdue_days * config('library.fine_per_day', 0.50);
    }

    public function canRenew(): bool
    {
        return $this->status === 'active'
            && $this->renewal_count < 2
            && !$this->isOverdue()
            && !$this->book->isReservedByUser($this->user_id);
    }

    public static function generateCode(): string
    {
        $year = date('Y');
        $last = self::where('borrow_code', 'like', "BRW-{$year}-%")->orderByDesc('id')->first();
        $seq = $last ? (int) substr($last->borrow_code, -5) + 1 : 1;
        return "BRW-{$year}-" . str_pad($seq, 5, '0', STR_PAD_LEFT);
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'active'   => ['label' => 'Active',    'class' => 'badge-info'],
            'returned' => ['label' => 'Returned',  'class' => 'badge-success'],
            'overdue'  => ['label' => 'Overdue',   'class' => 'badge-danger'],
            'lost'     => ['label' => 'Lost',      'class' => 'badge-secondary'],
            default    => ['label' => 'Unknown',   'class' => 'badge-light'],
        };
    }
}
