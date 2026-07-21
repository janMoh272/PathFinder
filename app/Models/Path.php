<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Path extends Model
{
    

public function question(){
   return $this->hasMany(question::class);
}
public function usersProfile(){
   return $this->belongsTo(user_profile::class);
}
}
