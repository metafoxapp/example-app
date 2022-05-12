<?php

namespace Company\Note\Http\Requests\v1\Category;

use MetaFox\Platform\Rules\PaginationLimitRule;
use MetaFox\Platform\Support\Helper\Pagination;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class IndexRequest.
 */
class IndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'id'    => ['sometimes', 'numeric', 'exists:note_categories,id'],
            'page'  => ['sometimes', 'numeric', 'min:1'],
            'limit' => ['sometimes', 'numeric', new PaginationLimitRule()],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function validated(): array
    {
        $data = parent::validated();

        if (!isset($data['id'])) {
            $data['id'] = 0;
        }

        if (!isset($data['limit'])) {
            $data['limit'] = Pagination::DEFAULT_ITEM_PER_PAGE;
        }

        return $data;
    }
}
