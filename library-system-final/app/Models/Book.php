<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'isbn', 'title', 'author', 'publisher', 'published_year',
        'category_id', 'description', 'cover_image', 'total_copies',
        'available_copies', 'reserved_copies', 'location', 'language',
        'pages', 'replacement_cost', 'status',
    ];

    protected $casts = [
        'replacement_cost' => 'decimal:2',
        'published_year'   => 'integer',
    ];

    // ==================== RELATIONSHIPS ====================

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

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

    // ==================== SCOPES ====================

    public function scopeAvailable($query)
    {
        return $query->where('available_copies', '>', 0)->where('status', 'available');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('author', 'like', "%{$term}%")
              ->orWhere('isbn', 'like', "%{$term}%")
              ->orWhere('publisher', 'like', "%{$term}%");
        });
    }

    // ==================== HELPERS ====================

    public function isAvailable(): bool
    {
        return $this->available_copies > 0 && $this->status === 'available';
    }

    public function getCoverUrlAttribute(): string
    {
        if ($this->cover_image && Storage::disk('public')->exists($this->cover_image)) {
            return route('covers.show', ['filename' => basename($this->cover_image)]);
        }

        $title = e(mb_strimwidth($this->title ?: 'Book', 0, 28, ''));
        $author = e(mb_strimwidth($this->author ?: 'Library', 0, 28, ''));

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 440">
    <defs>
        <linearGradient id="bg" x1="0" x2="1" y1="0" y2="1">
            <stop offset="0%" stop-color="#10233e" />
            <stop offset="100%" stop-color="#274d75" />
        </linearGradient>
    </defs>
    <rect width="300" height="440" rx="24" fill="url(#bg)" />
    <rect x="26" y="26" width="248" height="388" rx="18" fill="none" stroke="#d4b15d" stroke-width="3" opacity="0.9" />
    <text x="42" y="180" fill="#ffffff" font-family="Arial, sans-serif" font-size="26" font-weight="700">{$title}</text>
    <text x="42" y="218" fill="#d7e3f3" font-family="Arial, sans-serif" font-size="16">{$author}</text>
    <rect x="42" y="255" width="96" height="6" rx="3" fill="#d4b15d" />
    <text x="42" y="340" fill="#d4b15d" font-family="Arial, sans-serif" font-size="18" letter-spacing="2">LIBRARY</text>
</svg>
SVG;

        return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($svg);
    }

    public function getAvailabilityBadgeAttribute(): array
    {
        if ($this->available_copies > 0) {
            return ['label' => 'Available', 'class' => 'badge-success'];
        }
        if ($this->activeReservations()->count() > 0) {
            return ['label' => 'Reserved', 'class' => 'badge-warning'];
        }
        return ['label' => 'Unavailable', 'class' => 'badge-danger'];
    }

    public function isReservedByUser(int $userId): bool
    {
        return $this->reservations()
            ->where('user_id', $userId)
            ->whereIn('status', ['pending', 'ready'])
            ->exists();
    }

    public function isBorrowedByUser(int $userId): bool
    {
        return $this->borrows()
            ->where('user_id', $userId)
            ->whereIn('status', ['active', 'overdue'])
            ->exists();
    }
}
