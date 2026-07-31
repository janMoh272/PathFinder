<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestUserAnswer;
use App\Models\Path;
use App\Models\question;
use App\Models\test;
use App\Models\test_details;
use App\Models\user_answer;
use App\Models\user_profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(RequestUserAnswer $request)
    {
           $user=Auth::user();
                $total_score=0; 
                $total_errors=0;
                $total_time=0;
                $questionLimit=\Illuminate\Support\Env::get('TEST_QUESTION_LIMIT',3);
             
           $data=$request->validated();
  
           $CheckQuestion=question::find($data['question_id']);
             if(!$CheckQuestion) {
                return response()->json(['message'=>'not Found Question'], 404);}
        $IsCorrect= $CheckQuestion->correct_answer == $data['answer']?1:0;
         $answer= user_answer:: create([
                    'test_id'=>$data['test_id'],
                    'user_id'=>$user->id,
                    'question_id'=>$data['question_id'],
                    'time_spent'=>$data['time_spent'],
                    'answer'=>$data['answer'],
                    'is_correct'=>$IsCorrect
            ]);
    $test=test::find($data['test_id']);         
    if(!$test){
          return response()->json(['message'=>'not Found test'], 404);}
    if($test->user_id !==$user->id){
        return response()->json(['message'=>'Unauthorized'], 403); }
    $test->increment('current_question_index');
   




    if($test->current_question_index == $questionLimit){
     
       //get the total of speeding time///////////////////////////////////
       $total_time=user_answer::where('test_id',$test->id)->sum('time_spent');
       //get the total of correct answers//////////////////////
       $total_score=user_answer::where('test_id',$test->id)
                                ->where('is_correct',1)->count();
        //get the total of wrong answers////////////////////////////////////////
       $total_errors=user_answer::where('test_id',$test->id)
                                ->where('is_correct',0)->count();

                                $test->update([
                                    'status'=>'completed',
                                    'total_score'=>$total_score,
                                    'total_errors'=>$total_errors,
                                    'total_time'=>$total_time,
                                    'completed_at'=>now(),
                                    
                                ]);

 //start select the path for user      ////////////////////////////////////////
$pathfinder=null;
$impulsivity_score=null;

   //get the defult time for each question     ///////////////////////////////
   if($test->status=='completed'){
           $questionTime=test_details::where('test_id',$test->id)
                                         ->with('question')
                                         ->get();
       $questionTotal=test_details::where('test_id',$test->id)->count();                             
        $questionTimeAvr=$questionTime->avg('time_limit') ;
        $questionErrAvr=$questionTotal*0.25;


        if($total_time <= $questionTimeAvr && $total_errors <=$questionErrAvr){
                 $reflective=Path::where('name','reflective')->first();
                 $pathfinder=$reflective->id;
        }

         if($total_time > $questionTimeAvr && ($total_errors > $questionErrAvr||$total_errors <= $questionErrAvr)){
             $impulsive=Path::where('name','impulsive')->first();
             $pathfinder=$impulsive->id;

        }
     $user_profile=user_profile::create([
                'user_id'=>$user->id,
                'path_id'=> $pathfinder,
                'impulsivity_score'=>$impulsivity_score ,
                'total_errors'=>$total_errors ,
                'total_time'=>$total_time,
                'classified_at'=>now(),
       ]);
     return response()->json(['data'=>$test,'status'=>'completed','user_profile'=>$user_profile,
      'pathfinder'=>$pathfinder,], 200);
   }



    }




    







    return response()->json(['data'=>$answer,'message'=>'go to the next question'], 200);





    }

    /**
     * Display the specified resource.
     */
    public function show(user_answer $user_answer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(user_answer $user_answer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, user_answer $user_answer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(user_answer $user_answer)
    {
        //
    }
}
