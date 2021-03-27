<?php

namespace atelier\api\models;

class Participants extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'participants';
    protected $hidden = ['event_id'];
    protected $primaryKey = null;
    public $timestamps = false;
    public $incrementing = false;
}
