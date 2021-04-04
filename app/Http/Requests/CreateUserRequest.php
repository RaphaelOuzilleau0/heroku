<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'login' => 'required',
            'password' => 'required',
            'email' => 'required',
            'last_name' => 'required',
            'first_name' => 'required',
            'role_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'login.required' => 'Le pseudo est obligatoire',
            'password.required' => 'Le mot de passe est obligatoire',
            'email.required'  => "L'email est obligatoire",
            'last_name.required' => 'Le nom est obligatoire',
            'first_name.required' => 'Le prénom est obligatoire',
            'role_id.required' => 'Le mot de passe est obligatoire',
        ];
    
    }
}
