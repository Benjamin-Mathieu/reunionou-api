<?php
namespace backOffice\api\models;
use Illuminate\Database\Eloquent\SoftDeletes;
class Event extends \Illuminate\Database\Eloquent\Model
{
    use SoftDeletes;
    protected $table = 'event';
    protected $primaryKey = 'id';
    protected $hidden = ['deleted_at'];
    public $timestamps = true;

    public function creator()
    {
        return $this->belongsTo(User::class,'user_id')->select(array('id', 'name', 'firstname', 'mail'));
    }

    public function participants()
    {
        return $this->belongsToMany('backOffice\api\models\User','backOffice\api\models\Participants','event_id','user_id')->select(['firstname','name'])->withPivot('present');
    }
    public function messages()
    {
        return $this->hasMany(Message::class)->select(array('id','text','created_at','updated_at','user_id'));
    }

}