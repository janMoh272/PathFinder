<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class user_answer extends Model
{

protected $fillable = [
    'user_id','test_id','question_id','is_correct','time_spent','answered_at','answer',
];



public function user(){
    return $this->belongsTo(User::class);
}
public function test(){
    return $this->belongsTo(test::class);
}
public function question(){
    return $this->belongsTo(question::class);
}





 
}
