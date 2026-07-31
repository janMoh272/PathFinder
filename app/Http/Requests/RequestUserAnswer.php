<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RequestUserAnswer extends FormRequest
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
        return [
                            'test_id'=>'required|exists:tests,id',
                            'question_id'=>'required|exists:questions,id',
                            'time_spent'=>'required',
                            'answer'=>'required',
        ];
    }

public function messages()
{
    return
    [
                'test_id.required'=> 'هذا الحقل مطلوب'  ,
                'question_id.required'=> 'هذا الحقل مطلوب'  ,
                'time_spent.required'=> 'هذا الحقل مطلوب'  ,
                'answer.required'=> 'هذا الحقل مطلوب'  ,
                
               

    ];
}

}
