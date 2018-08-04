<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['id'];
  
    public function jobGroups(){
        return $this->hasOne('App\JobGroup');
    }
    
    public function hours() {
        return $this->hasMany('App\Hour');
    }
}
