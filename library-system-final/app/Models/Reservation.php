<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model {
    use HasFactory;
    protected $fillable = ['reservation_code','user_id','book_id','reserved_at','expires_at','notified_at','status','queue_position','notes'];
    protected $casts = ['reserved_at'=>'datetime','expires_at'=>'datetime','notified_at'=>'datetime'];
    public function user() { return $this->belongsTo(User::class); }
    public function book() { return $this->belongsTo(Book::class); }
    public function isExpired(): bool { return $this->expires_at < now() && in_array($this->status, ['pending','ready']); }
    public function getStatusBadgeAttribute(): array { return match($this->status) { 'pending'=>['label'=>'Pending','class'=>'badge-warning'],'ready'=>['label'=>'Ready','class'=>'badge-success'],'fulfilled'=>['label'=>'Fulfilled','class'=>'badge-info'],'cancelled'=>['label'=>'Cancelled','class'=>'badge-secondary'],'expired'=>['label'=>'Expired','class'=>'badge-danger'],default=>['label'=>'Unknown','class'=>'badge-light'] }; }
    public static function generateCode(): string { $year=date('Y'); $last=self::where('reservation_code','like',"RES-{$year}-%")->orderByDesc('id')->first(); $seq=$last?(int)substr($last->reservation_code,-5)+1:1; return "RES-{$year}-".str_pad($seq,5,'0',STR_PAD_LEFT); }
}
