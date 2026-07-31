<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestQuestions;
use App\Models\question;
use Illuminate\Http\Request;


class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $question=question::with('path')->get();
       // $image_url= $question->text; can't use this for alot of info
      // return  response()->json(['data'=>$question], 200);
        return response()->json([
            //
            'data'=>$question->map(function($q) {
                     return[
                        'id'=>$q->id,
                        'type'=>$q->type,
                        'content_type'=>$q->content_type,
                        'text'=>$q->content_type === 'image'?asset('storage/'.$q->text):$q->text,
                        'correct_answer'=>$q->content_type === 'image'?asset('storage/'.$q->correct_answer):$q->correct_answer,
                        'options'=>$q->content_type === 'image'?collect(json_decode($q->options,true))->map(function ($path) {
                             return asset('storage/'.$path);
                             //استخدام الكولكشن من اجل الاستفادة من دوال لارافيل مثل ماب 
                        })->all():json_decode($q->options,true),
                        'difficulty'=>$q->difficulty,
                        'path'=>$q->path,
                        //'path_name'=>$q->path->name,
                        'time_limit'     => $q->time_limit,
                         'explanation'    => $q->explanation,
                        'is_active'      => $q->is_active,



  
                       
                         
                     ];
        })], 200); 
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
    public function store(RequestQuestions $request)
    {
    
      $data=$request->validated();
     
//add main pic////////////////////////////////////////////////////////
      if($request->hasFile('text')){
        $imagepath=$request->file('text')->store('quastions','public');
        $data['text']=$imagepath;
      }else{
         $data['text']=$request->text;
      }
      
      $optionsPaths = [];
//add corrrect answer////////////////////////////////////////////
         if($request->hasFile('correct_answer')){
            $correct_answer_Path=$request->file('correct_answer')->store('correct_answer','public');
            $data['correct_answer']=$correct_answer_Path;
            $optionsPaths[]=$correct_answer_Path;
          // $correct_answer_name= time().'correct_answer'.'.'->getClientOriginalName();
      }else{
         $data['correct_answer']=$request->correct_answer;
      }
//add options//////////////////////////////////////////////////////////////
// تفريغ مصفوفة المسارات لبدء التخزين

// 🎯 الفحص الذكي: إذا كان حقل options يحتوي على ملفات مرفوعة حقيقية
if($request->hasFile('options')){
     $files=$request->file('options');
     foreach($files as $index=>$file){
         if($file && $file->isValid()){
           // $optionName=time().'_option_'.$index.'.'. $file->getClientOriginalExtension();
            $optionPath=$file->store('options','public');
            $optionsPaths[]=$optionPath;
         }
     }
}else{
        // 🎯 إذا لم تكن ملفات، فهي نصوص عادية (MCQ) قادمة من الواجهة
    $rawOptions = $request->input('options');
    
    if (is_string($rawOptions)) {
        // إذا كانت قادمة كنص JSON ممرر من الجافا سكريبت، نقوم بفكها
        $decoded = json_decode($rawOptions, true);
        $optionsPaths = is_array($decoded) ? $decoded : [$rawOptions];
    } elseif (is_array($rawOptions)) {
        $optionsPaths[] = $rawOptions;
    }

}

// دمج المصفوفة النهائية في حقل الخيارات تمهيداً لحفظها
$data['options'] = json_encode($optionsPaths, JSON_UNESCAPED_UNICODE);
     $question = question::create($data);
    return response()->json(['message' => 'تم حفظ السؤال بنجاح!', 
                              'data' => $question,], 201);
   
    }

    /**
     * Display the specified resource.
     */
    public function show(question $question)
    {

        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RequestQuestions $request,question $question)
    {
   

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequestQuestions $request, question $question)
    {
        
     // $data=$request->validated();
     
//update main pic////////////////////////////////////////////////////////
      if($request->hasFile('text')){
        $imagepath=$request->file('text')->store('quastions','public');
        $question->text=$imagepath;
      }else{
         $question->text=$request->input('text',$question->text);
      }
//update corrrect answer////////////////////////////////////////////
         if($request->hasFile('correct_answer')){
            $correct_answer_Path=$request->file('correct_answer')->store('correct_answer','public');
            $question->correct_answer=$correct_answer_Path;
      }else{
         $question->correct_answer=$request->input('correct_answer',$question->correct_answer);
      }
//add options//////////////////////////////////////////////////////////////
// تفريغ مصفوفة المسارات لبدء التخزين
$optionsPaths = null;
// 🎯 استخدام دالخ saveAs  من اجل تحديد الاسم و مكان ااتخزين
if($request->hasFile('options')){
    $optionsPaths=[];
     $files=$request->file('options');
     foreach($files as $index=>$file){
         if($file && $file->isValid()){
            $optionName=time().'_option_'.$index.'.'. $file->getClientOriginalExtension();
            $optionPath=$file->storeAs('options',$optionName,'public');
            $optionsPaths[]=$optionPath;
         }
     }
}elseif($request->has('options')){
        // 🎯 إذا لم تكن ملفات، فهي نصوص عادية (MCQ) قادمة من الواجهة
    $rawOptions = $request->input('options');
    
    if (is_string($rawOptions)) {
        // إذا كانت قادمة كنص JSON ممرر من الجافا سكريبت، نقوم بفكها
        $decoded = json_decode($rawOptions, true);
        $optionsPaths = is_array($decoded) ? $decoded : [$rawOptions];
    } elseif (is_array($rawOptions)) {
        $optionsPaths = $rawOptions;
    }

}if($optionsPaths!==null){
    $question->options= json_encode($optionsPaths, JSON_UNESCAPED_UNICODE);
}
else{
$question->options=$question->options;
}

//   في هذه الحاله يفضل استخدام input بدلامن requst->text مباشرا من اجل اذا لم يتم ارسال طلب لايظهر خطاء بل يحتفض بما في قاعدة البانات 

$question->type=$request->input('type',$question->type);
$question->content_type=$request->input('content_type',$question->content_type);
$question->difficulty=$request->input('difficulty',$question->difficulty);
$question->path_id=$request->input('path_id',$question->path_id);
$question->is_active=$request->input('is_active',$question->is_active);
$question->explanation=$request->input('explanation',$question->explanation);
$question->time_limit=$request->input('time_limit',$question->time_limit);
$question->save();
    return response()->json(['message' => 'تم تعديل السؤال بنجاح!', 
                              'data' => $question,], 201);
    //      $question->update($request->validated());
    //    return response()->json(['message'=>'ـم التعديل بنجاح' ,"data"=>$question] ,   200, ); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(question $question)
    {
        $question->delete($question);
        return response()->json(['message'=>'Deleting Seccefully'], 201);
    }
}
