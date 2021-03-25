<?php
namespace atelier\api\models;

class Event extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'event';
    protected $primaryKey = 'id';
    public $timestamps = true;

    public function creator()
    {
        return $this->hasOne(User::class,'id');
    }

    public function participants()
    {
        return $this->belongsToMany('atelier\api\models\User','atelier\api\models\Event','user_id','event_id');
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

}