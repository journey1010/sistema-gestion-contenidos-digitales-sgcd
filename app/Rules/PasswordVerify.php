<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PasswordVerify implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{5,}$/', $value)){
            $fail('Formato de contraseña incorrecto. Debe contener minusculas, mayusculas y numeros');
        }
    }   
}
