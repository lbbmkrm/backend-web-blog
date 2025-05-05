<?php

namespace App\Http\Requests\Blogs;

use App\Models\Blog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

/**
 * @method bool hasFile(string $key)
 * @method array only(array|string $keys)
 * @method \Illuminate\Http\UploadedFile|null file(string $key = null, $default = null)
 */

class StoreBlogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', Blog::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'category_id' => 'sometimes|exists:categories,id',
            'content' => 'sometimes|string',
            'description' => 'sometimes|nullable|string',
            'thumbnail' => 'sometimes|nullable|image|max:2048',
        ];
    }
}
