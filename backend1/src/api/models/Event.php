<?php
namespace atelier\api\models;

class Event extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'event';
    protected $primaryKey = 'id';
    public $timestamps = true;

    public function participants()
    {
        return $this->hasMany(User::class,'id');
    }
}