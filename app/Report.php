<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['id','job_group_id'];
    //
    public function hours(){
      return $this->hasMany('App\Hour');
    }
}
