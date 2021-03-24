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

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

}