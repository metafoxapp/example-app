<?php

namespace Company\Note\Http\Controllers\Api;

use MetaFox\Platform\Http\Controllers\Api\GatewayController;

/**
 * --------------------------------------------------------------------------
 *  Api Gateway
 * --------------------------------------------------------------------------.
 *
 * This class solve api versioning problem.
 * DO NOT IMPLEMENT ACTION HERE.
 */

/**
 * Class CategoryController.
 */
class CategoryController extends GatewayController
{
    /**
     * @var string[]
     */
    protected $controllers = [
        'v1'   => v1\CategoryController::class,
    ];

    // DO NOT IMPLEMENT ACTION HERE.
}
