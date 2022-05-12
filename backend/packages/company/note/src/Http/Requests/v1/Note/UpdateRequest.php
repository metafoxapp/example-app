<?php

namespace Company\Note\Http\Requests\v1\Note;

use Illuminate\Foundation\Http\FormRequest;
use MetaFox\Platform\Rules\AllowInRule;
use MetaFox\Platform\Rules\ExistIfGreaterThanZero;
use MetaFox\Platform\Rules\v4\PrivacyRule;
use MetaFox\Platform\Traits\Http\Request\PrivacyRequestTrait;

/**
 * Class UpdateRequest.
 */
class UpdateRequest extends FormRequest
{
    use PrivacyRequestTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title'            => ['sometimes', 'string', 'between:3,255'],
            'categories'       => ['sometimes', 'array'],
            'categories.*'     => ['numeric', 'exists:note_categories,id'],
            'attachments'      => ['sometimes', 'array'],
            'attachments.*.id' => ['sometimes', 'numeric', 'exists:core_attachments,id'],
            'file'             => ['sometimes', 'array'],
            'file.temp_file'   => [
                'required_if:file.status,update', 'numeric', new ExistIfGreaterThanZero('exists:temp_files,id'),
            ],
            'file.status'      => ['required_with:file', 'string', new AllowInRule(['update', 'remove'])],
            'text'             => ['sometimes', 'string'],
            'tags'             => ['sometimes', 'array'],
            'tags.*'           => ['string'],
            'draft'            => ['sometimes', 'numeric', new AllowInRule([0, 1])],
            'published'        => ['sometimes', 'boolean'],
            'privacy'          => ['sometimes', new PrivacyRule()],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function validated(): array
    {
        $data = parent::validated();

        $data = $this->handlePrivacy($data);

        if (isset($data['draft'])) {
            $data['is_draft'] = $data['draft'];
        }

        $data['temp_file'] = 0;
        if (isset($data['file']['temp_file'])) {
            $data['temp_file'] = $data['file']['temp_file'];
        }

        $data['remove_image'] = false;
        if (isset($data['file']['status'])) {
            $data['remove_image'] = true;
        }

        return $data;
    }
}
