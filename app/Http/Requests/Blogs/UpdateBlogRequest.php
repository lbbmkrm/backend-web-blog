<?php

namespace App\Http\Requests\Blogs;

use App\Models\Blog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

/**
 * @method bool hasFile(string $key)
 * @method \Illuminate\Http\UploadedFile|null file(string $key = null, $default = null)
 * @method array all(mixed $keys = null)
 * @method array only(array|string $keys)
 * @method bool filled(array|string $key)
 * @method mixed input(string $key = null, $default = null)
 */
class UpdateBlogRequest extends FormRequest
{
    /**
     * @method \App\Models\Blog route(string $key = 'blogId')
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
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255|distinct'
        ];
    }
}
