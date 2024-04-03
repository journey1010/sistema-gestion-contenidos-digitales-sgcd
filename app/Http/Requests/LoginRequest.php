<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Rules\isUserActive;
use App\Rules\PasswordVerify;

class LoginRequest extends FormRequest
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
        $jsonResponse  = new JsonResponse([
            'status' => 'error',
            'message' => messageValidation($validator),
        ]);

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
            'email' => ['required', 'email', new isUserActive ],
            'password' => ['required', 'string', new PasswordVerify],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Sin correo tílin no hay pase',
            'email.email' => 'Correo no valido',
            'password.required' => 'Sin contraseña',
            'password.string' => 'Contraseña debe ser de tipo string',
        ];
    }
}
