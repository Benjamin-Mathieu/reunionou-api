<?php

namespace atelier\api\models;

class Participants extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'participants';
<<<<<<< HEAD
    protected $fillable = ['user_id', 'event_id'];
=======
    protected $hidden = ['event_id'];
    protected $primaryKey = null;
>>>>>>> 2ed7ae2085e9178b056b2f95bec6955e2ce27141
    public $timestamps = false;
    public $incrementing = false;
}
