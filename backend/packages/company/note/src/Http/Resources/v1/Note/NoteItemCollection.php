<?php

namespace Company\Note\Http\Resources\v1\Note;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NoteItemCollection extends ResourceCollection
{
    protected $collect = NoteItem::class;
}
