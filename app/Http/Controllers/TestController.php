<?php

namespace App\Http\Controllers;

use App\Models\question;
use App\Models\test;
use App\Models\test_details;
use App\Models\User;
use App\Models\user_answer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
  
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {     /** @var /App/Models/User $user */
             $user= Auth::user();
             $questionLimit=\Illuminate\Support\Env::get('TEST_QUESTION_LIMIT',3);

    //   if user done the quiz////////////////////////////////////////////////////////////////
             $haveDone=test::where('user_id',$user->id)
                                ->where('status','completed')
                                ->first();
             if($haveDone){
                return response()->json(['message'=>'You have done ','status'=>$haveDone->status], 200);
             }   
             
  //  if user start but not complete the quiz//////////////////////////////////////////////////////////////// 
         
      $activeTest=test::where('user_id',$user->id)->where('status','in_progress')->first();
            if($activeTest && $activeTest->current_question_index  < $questionLimit) {
                 $userAnswer=user_answer::where('test_id',$activeTest->id)->pluck('question_id') ->toArray();
                      $testDetails=test_details::where('test_id',$activeTest->id)
                                                 ->whereNotIn('question_id', $userAnswer)
                                               ->with('question') 
                                               ->get(); 
                      $currentIndex=$activeTest->current_question_index;       
                       return response()->json(['testDetails'=>$testDetails,'currentIndex'=>$currentIndex], 200); } 
//  Frist log in for the user////////////////////////////////////////////////////////////////            
            if(!$activeTest){  
     $questions=question::whereNull('path_id')->where('is_active',1)->inRandomOrder()->limit($questionLimit) ->get(); 
                     if(!$questions){
                         return response()->json(['message'=>'there is no enogh question'], 404);
                     }else{
                           $newTest=test::create([
                                    'user_id'=>$user->id,
                                    'type'=>'classification',
                                    'started_at'=>now(),
                                    'status'=>'in_progress',
                                      ]);
          foreach($questions as $index=>$question){
                            $newTestDetials=test_details::create([
                                    'test_id'=>$newTest->id,
                                    'question_id'=>$question->id,
                                    'order_num'=>$index,
                                    'points'=>1, ]);  
                                      }
          return response()->json(['allQuestions'=>$questions,'index'=>0,'new test '=>$newTest,'newDetails'=>$newTestDetials], 200);
                                    }
                                    

             
                               
            }



        

    }

    /**
     * Display the specified resource.
     */
    public function show(test $test)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(test $test)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, test $test)
    {
 


  
      
       

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(test $test)
    {
        //
    }
}
