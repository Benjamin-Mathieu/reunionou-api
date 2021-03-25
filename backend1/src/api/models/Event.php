<?php
namespace atelier\api\models;

class Event extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'event';
    protected $primaryKey = 'id';
    public $timestamps = true;

    public function creator()
    {
        return $this->belongsTo(User::class,'user_id')->select(array('id', 'name', 'firstname', 'mail'));
    }

    public function participants()
    {
        return $this->belongsToMany('atelier\api\models\User','atelier\api\models\Participants','event_id','user_id')->select(['firstname','name'])->withPivot('present');
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

}