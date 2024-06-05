<?php

namespace App\Http\Requests\Doc;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class SaveDocRequest extends FormRequest
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
            'appName' => 'required|string', 
            'typeDoc' => 'required|numeric',
            'userId' => 'required|numeric',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'file' => 'mimes:doc,docx,xlsx,xls,pdf'
        ];
    }

    public function message()
    {
        return [
            'typeDoc.required' => 'Tipo de documento es requerido.',
            'typeDoc.numeric' => 'Tipo de documento es númerico.',
            'title.required' => 'Título es necesario',
            'title.string' => 'Formato de Título invalido',
            'title.max' => 'El título debe tener un máximo de 255 caracteres',
            'description.required' => 'Descripción debe ser alfabetica',
            'description.max' => 'Descripción debe tener un máximo de 1000 caracteres'
        ];
    }
}
