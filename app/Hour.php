<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hour extends Model
{
    //
  
    public function employees(){
      return $this->belongsTo('App\Employee');
    }
    
    public function reports() {
      return $this->belongsTo('App\Report');
    }
}
