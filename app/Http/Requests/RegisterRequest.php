<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\EmailVerify;
use App\Rules\PasswordVerify;

class RegisterRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        $jsonResponse = new JsonResponse([
            'status' => 'error',
            'message' => messageValidation($validator),
        ], 422);
        
        throw new HttpResponseException($jsonResponse);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => ['required','email', new EmailVerify],
            'password' => ['required', new PasswordVerify],
            'rol' => 'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Un nombre completo es requerido',
            'email.required' =>  'Un email es requerido',
            'password.required' => 'Una contraseña es requerida',
            'rol.required' => 'un rol es requerido'
        ];
    }
}