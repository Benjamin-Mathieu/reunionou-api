<?php
namespace backOffice\api\models;

class UserAdmin extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'useradmin';
    protected $primaryKey = 'id';
    public $timestamps = false;
}