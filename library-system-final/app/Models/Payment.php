<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
    use HasFactory;
    protected $fillable = ['payment_code','user_id','fine_id','amount','method','status','transaction_ref','processed_by','notes'];
    protected $casts = ['amount'=>'decimal:2'];
    public function user() { return $this->belongsTo(User::class); }
    public function fine() { return $this->belongsTo(Fine::class); }
    public function processedBy() { return $this->belongsTo(User::class,'processed_by'); }
    public static function generateCode(): string { $year=date('Y'); $last=self::where('payment_code','like',"PAY-{$year}-%")->orderByDesc('id')->first(); $seq=$last?(int)substr($last->payment_code,-5)+1:1; return "PAY-{$year}-".str_pad($seq,5,'0',STR_PAD_LEFT); }
}
