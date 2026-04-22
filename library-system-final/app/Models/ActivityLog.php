<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model {
    use HasFactory;
    protected $fillable = ['user_id','action','subject_type','subject_id','description','ip_address'];
    public function user() { return $this->belongsTo(User::class); }
    public static function log(string $action, string $description, $subject = null, $userId = null): void {
        self::create(['user_id'=>$userId??auth()->id(),'action'=>$action,'subject_type'=>$subject?get_class($subject):null,'subject_id'=>$subject?->id,'description'=>$description,'ip_address'=>request()->ip()]);
    }
}
