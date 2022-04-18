<?php

namespace Company\Note\Http\Requests\v1\Note;

use Illuminate\Foundation\Http\FormRequest;

/**
 * --------------------------------------------------------------------------
 *  Http request for api version v1
 * --------------------------------------------------------------------------.
 *
 * This class is used by automatic dependency injection:
 *
 * @link \Company\Note\Http\Controllers\Api\v1\NoteController::note;
 * stub: /modules/requests/api_action_request.stub
 */

/**
 * Class SearchFormRequest.
 */
class SearchFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'page'  => ['sometimes', 'numeric', 'min:1'],
            'limit' => ['sometimes', 'numeric', 'min:10'],
        ];
    }
}
