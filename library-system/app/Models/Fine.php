<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fine extends Model {
    use HasFactory;
    protected $fillable = ['user_id','borrow_id','type','overdue_days','amount','paid_amount','status','reason'];
    protected $casts = ['amount'=>'decimal:2','paid_amount'=>'decimal:2'];
    public function user() { return $this->belongsTo(User::class); }
    public function borrow() { return $this->belongsTo(Borrow::class); }
    public function payments() { return $this->hasMany(Payment::class); }
    public function getRemainingAttribute(): float { return max(0, $this->amount - $this->paid_amount); }
    public function getStatusBadgeAttribute(): array { return match($this->status) { 'unpaid'=>['label'=>'Unpaid','class'=>'badge-danger'],'partial'=>['label'=>'Partial','class'=>'badge-warning'],'paid'=>['label'=>'Paid','class'=>'badge-success'],'waived'=>['label'=>'Waived','class'=>'badge-info'],default=>['label'=>'Unknown','class'=>'badge-light'] }; }
}
