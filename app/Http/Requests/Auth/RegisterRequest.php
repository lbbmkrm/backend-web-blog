<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @method bool hasFile(string $key)
 * @method array only(array|string $keys)
 * @method \Illuminate\Http\UploadedFile|null file(string $key = null, $default = null)
 */
class RegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_number' => 'required|numeric',
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8'
        ];
    }
}
