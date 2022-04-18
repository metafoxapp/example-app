<?php

namespace Company\Note\Http\Requests\v1\Note;

use Illuminate\Foundation\Http\FormRequest;
use MetaFox\Platform\Rules\AllowInRule;
use MetaFox\Platform\Rules\ExistIfGreaterThanZero;
use MetaFox\Platform\Rules\ResourceTextRule;
use MetaFox\Platform\Rules\v4\PrivacyRule;
use MetaFox\Platform\Traits\Http\Request\PrivacyRequestTrait;
use Modules\Core\Repositories\TagRepositoryInterface;
use Modules\Core\Rules\RecaptchaV3Rule;

/**
 * Class StoreRequest.
 */
class StoreRequest extends FormRequest
{
    use PrivacyRequestTrait;

    public const ACTION_CAPTCHA_NAME = 'create-note';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'title'             => ['required', 'string', 'between:1,255'],
            'categories'        => ['sometimes', 'array'],
            'categories.*'      => ['numeric', 'exists:note_categories,id'],
            'attachments'       => ['sometimes', 'array'],
            'attachments.*.id'  => ['numeric', 'exists:core_attachments,id'],
            'owner_id'          => ['sometimes', 'numeric', new ExistIfGreaterThanZero('exists:user_entities,id')],
            'file'              => ['sometimes', 'array'],
            'file.temp_file'    => ['required_with:file', 'numeric', 'exists:temp_files,id'],
            'text'              => ['required', 'string', new ResourceTextRule()],
            'draft'             => ['sometimes', 'numeric', new AllowInRule([0, 1])],
            'tags'              => ['sometimes', 'array'],
            'tags.*'            => ['string'],
            'privacy'           => ['required', new PrivacyRule()],
        ];

        if (hasCaptcha('note.enable_captcha_challenge_when_adding_a_new_note')) {
            $rules['captcha'] = ['required', new RecaptchaV3Rule(self::ACTION_CAPTCHA_NAME)];
        }

        return $rules;
    }

    /**
     * @return array<string, mixed>
     */
    public function validated(): array
    {
        $data = parent::validated();

        $data = $this->handlePrivacy($data);

        $data['is_draft'] = 0;
        if (isset($data['draft'])) {
            $data['is_draft'] = $data['draft'];
        }

        $data['temp_file'] = 0;
        if (isset($data['file']['temp_file'])) {
            $data['temp_file'] = $data['file']['temp_file'];
        }

        if (empty($data['owner_id'])) {
            $data['owner_id'] = 0;
        }

        return $data;
    }
}
