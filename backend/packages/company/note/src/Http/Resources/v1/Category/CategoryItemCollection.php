<?php

namespace Company\Note\Http\Resources\v1\Category;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryItemCollection extends ResourceCollection
{
    protected string $collect = CategoryItem::class;
}
