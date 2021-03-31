<?php
namespace atelier\api\models;
use Illuminate\Database\Eloquent\SoftDeletes;
class Message extends \Illuminate\Database\Eloquent\Model
{
    use SoftDeletes;
    protected $table = 'message';
    protected $primaryKey = 'id';
    public $timestamps = true;

    public function sender()
    {
        return $this->belongsTo(User::class,'user_id')->select(array('name','firstname','mail'));
    }
}