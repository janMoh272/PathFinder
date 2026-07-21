<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class test extends Model
{
    protected $fillable=[
            'user_id' ,
            'type', 
            'started_at' ,
            'completed_at' ,
            'status',
            'total_score' ,
            'total_errors' ,
            'total_time' ,
            'current_question_index' ,
    ];


public function user(){
   return $this->belongsTo(User::class,'user_id');

}

public function  userAnswers(){
    return $this->hasMany(user_answer::class);
}

}
