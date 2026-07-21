<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class user_profile extends Model
{
    protected $fillable = ['user_id','path_id','impulsivity_score','total_errors','total_time', 'classified_at'];




    public function path() 
    {
        return $this->hasMany(Path::class);
    }




}
