<?php

namespace App\Http\Requests;

//use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use function Pest\Laravel\put;

class RequestQuestions extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         if($this->options!=='image'  && $this->has('options')&&is_string($this->options)){
            $this->merge(['options'=>json_decode($this->options,true)]);

         }
        $isUpdate=$this->isMethod('put');
        $isImage=$this->input('content_type')==='image';
        return [
                'text'=>$isImage
                ?($isUpdate?['sometimes','image','mimes:png,jpg','max:2048',]:['required','image','mimes:png,jpg','max:2048',])
                :($isUpdate?['sometimes','string','between:5,150',]:['required','string','between:5,150',]),
              
                'type'=>[$isUpdate?'sometimes':'required','string',Rule::in(['mcq', 'truefalse', 'matching', 'production']),],
                'content_type'=>[$isUpdate?'sometimes':'required','string',Rule::in(['text','image']),],
                'difficulty'=>['numeric','between:1,5',],

                'correct_answer'=>$isImage
                ?($isUpdate?['sometimes','image','mimes:png,jpg','max:2048']:['required','image','mimes:png,jpg','max:2048',])
                :($isUpdate?['sometimes','string']:['required','string']),

                'path_id'=>['nullable','exists:paths,id',],
                'options'=>$isImage
                ?($isUpdate?['sometimes','array']:['required',])
                :($isUpdate?['sometimes','array']:['required',]),
                'options.*'=>$isImage?['image','mimes:png,jpg','max:2048']:['sometimes','required_if:type,mcq,matching',],
                'time_limit'=>['nullable',],
                'explanation'=>['nullable',],
                'is_active'=>['boolean',],
        ];
    }

    public function messages()
    {
       
        return[
                'text.required'=> 'هذا الحقل مطلوب'  ,
                'text.between'=>'   يجب ان يكون النص بين 10 الى 150 حرفا' ,
                'text.string'=>' هذا الحقل يجب ان يكون نصا   ' ,
                'type.required'=>' هذا الحقل مطلوب' ,
                 'difficulty.numeric'=>'هذا الحقل رقم',
                 'difficulty.between'=>'يجب ان يكون بين ال1 و 5',
                 'correct_answer.required'=>' هذا الحقل مطلوب' ,
                 'correct_answer.string'=>' يجب ان يكون هذا لاالحقل نصا ' 
              
        ];
    }
}
