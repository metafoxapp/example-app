<?php

namespace Company\Note\Http\Resources\v1\Note;

use Illuminate\Http\Resources\Json\ResourceCollection;

/*
|--------------------------------------------------------------------------
| Resource Collection
|--------------------------------------------------------------------------
|
| @link https://laravel.com/docs/8.x/eloquent-resources#concept-overview
| @link /app/Console/Commands/stubs/module/resources/detail.stub
|
*/

class NoteEmbedCollection extends ResourceCollection
{
    /** @var string */
    protected $collect = NoteEmbed::class;
}
