<?php

namespace Company\Note\Http\Requests\v1\Category;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreRequest.
 */
class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'      => ['required', 'string', 'between:3,255'],
            'name_url'  => ['sometimes', 'string', 'between:3,255'],
            'is_active' => ['sometimes', 'numeric'],
            'ordering'  => ['sometimes', 'numeric'],
            'parent_id' => ['nullable', 'numeric', 'exists:note_categories,id'],
        ];
    }
}
