<?php
namespace atelier\api\models;

class User extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id'];
    public $timestamps = true;

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function participants()
    {
        return $this->belongsToMany('atelier\api\models\Event','atelier\api\models\Participants','user_id','event_id')->withPivot('present');
    }
}