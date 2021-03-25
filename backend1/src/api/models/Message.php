<?php
namespace atelier\api\models;

class Message extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'message';
    protected $primaryKey = 'id';
    public $timestamps = true;

    public function sender()
    {
        return $this->belongsTo(User::class);
    }
}