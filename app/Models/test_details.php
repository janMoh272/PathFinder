<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class test_details extends Model
{
protected $fillable=[
'test_id','question_id','order_num','points',
];


/**
     * Get the question that owns the test_details
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo;
     */
    public function question()
    {
        return $this->belongsTo(question::class);
    }

     
}
