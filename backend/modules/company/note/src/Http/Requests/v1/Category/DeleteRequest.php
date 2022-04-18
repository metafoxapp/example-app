<?php

namespace Company\Note\Http\Requests\v1\Category;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class DeleteRequest.
 */
class DeleteRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'new_category_id' => ['sometimes', 'numeric', 'exists:note_categories,id'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function validated(): array
    {
        $data = parent::validated();

        if (!isset($data['new_category_id'])) {
            $data['new_category_id'] = 0;
        }

        return $data;
    }
}
