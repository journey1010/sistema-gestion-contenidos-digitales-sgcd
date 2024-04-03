<?php

namespace App\Http\Requests\Doc;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class TypeDoc extends FormRequest
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
            'description' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Un nombre para el tipo de documento es requerido',
            'description.string' => 'La descripción es textual',
            'description.max' => 'La descripción no debe exceder los 255 caracteres'
        ];
    }
}
