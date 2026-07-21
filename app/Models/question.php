<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class question extends Model
{
    protected $fillable=[
                'text',
                'type',
                'difficulty',
                'correct_answer',
                'path_id',
                'options',
                'time_limit',
                'explanation',
                'is_active',
                'content_type'
    ];


    public function path(){
        return $this->belongsTo(Path::class,'path_id');
    }
    public function testDetails(){
        return $this->hasMany(test_details::class);
    }
}
