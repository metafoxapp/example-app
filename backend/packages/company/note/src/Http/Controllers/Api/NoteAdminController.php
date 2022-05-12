<?php

namespace Company\Note\Http\Controllers\Api;

use MetaFox\Platform\Http\Controllers\Api\GatewayController;

/**
 | --------------------------------------------------------------------------
 |  Api Gateway
 | --------------------------------------------------------------------------.
 |
 | This class solve api versioning problem.
 | DO NOT IMPLEMENT ACTION HERE.
 | stub: /packages/controllers/admin_api_gateway.stub
 */

/**
 * Class NoteAdminController.
 */
class NoteAdminController extends GatewayController
{
    /**
     * @var string[]
     */
    protected $controllers = [
        'v1'   => v1\NoteController::class,
    ];

    // DO NOT IMPLEMENT ACTION HERE.
}
