<?php

namespace Company\Note\Http\Requests\v1\Note;

use Illuminate\Support\Str;
use MetaFox\Platform\PhpfoxConstant;
use MetaFox\Platform\Rules\AllowInRule;
use MetaFox\Platform\Rules\PaginationLimitRule;
use MetaFox\Platform\Support\Browse\Scopes\SortScope;
use MetaFox\Platform\Support\Browse\Scopes\WhenScope;
use MetaFox\Platform\Support\Helper\Pagination;
use Illuminate\Foundation\Http\FormRequest;
use Company\Note\Support\Browse\Scopes\Note\ViewScope;

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
            'q'           => ['sometimes', 'string'],
            'view'        => ['sometimes', 'string', new AllowInRule(ViewScope::getAllowView())],
            'sort'        => ['sometimes', 'string', new AllowInRule(SortScope::getAllowSort())],
            'sort_type'   => ['sometimes', 'string', new AllowInRule(SortScope::getAllowSortType())],
            'when'        => ['sometimes', 'string', new AllowInRule(WhenScope::getAllowWhen())],
            'category_id' => ['sometimes', 'numeric', 'exists:note_categories,id'],
            'user_id'     => ['sometimes', 'numeric', 'exists:user_entities,id'],
            'page'        => ['sometimes', 'numeric', 'min:1'],
            'limit'       => ['sometimes', 'numeric', new PaginationLimitRule()],
        ];
    }

    /**
     * @return array<string, mixed>
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function validated(): array
    {
        $data = parent::validated();

        if (!isset($data['view'])) {
            $data['view'] = ViewScope::VIEW_DEFAULT;
        }

        if (!isset($data['sort'])) {
            $data['sort'] = SortScope::SORT_DEFAULT;
        }

        if (!isset($data['sort_type'])) {
            $data['sort_type'] = SortScope::SORT_TYPE_DEFAULT;
        }

        if (!isset($data['when'])) {
            $data['when'] = WhenScope::WHEN_DEFAULT;
        }

        if (!isset($data['limit'])) {
            $data['limit'] = Pagination::DEFAULT_ITEM_PER_PAGE;
        }

        if (!isset($data['category_id'])) {
            $data['category_id'] = 0;
        }

        if (!isset($data['user_id'])) {
            $data['user_id'] = 0;
        }

        if (!isset($data['q'])) {
            $data['q'] = PhpfoxConstant::EMPTY_STRING;
        }

        if (Str::startsWith($data['q'], '#')) {
            $data['tag'] = Str::substr($data['q'], 1);
            $data['q'] = PhpfoxConstant::EMPTY_STRING;
        }

        return $data;
    }
}
