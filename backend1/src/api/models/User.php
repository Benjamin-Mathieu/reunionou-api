<?php
namespace atelier\api\models;

class User extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    public $timestamps = true;

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}