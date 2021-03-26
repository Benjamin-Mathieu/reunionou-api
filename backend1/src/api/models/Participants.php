<?php
namespace atelier\api\models;

class Participants extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'participants';
    protected $fillable = ['user_id'];
    public $timestamps = false;
    public $incrementing = false;
}