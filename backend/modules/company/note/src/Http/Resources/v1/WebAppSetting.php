<?php
/**
 * @author  developer@phpfox.com
 * @license phpfox.com
 */

namespace Company\Note\Http\Resources\v1;

use MetaFox\Platform\Support\Resource\WebAppSetting as AppSetting;

/**
 *--------------------------------------------------------------------------
 * Web Resource Config Gateway
 * Inject this class to @link \Company\Note\Listeners\ModuleSettingListener::getWebAppSettings()
 *--------------------------------------------------------------------------
 * stub: /modules/resources/app_setting.stub.
 */

/**
 * Class WebAppSetting.
 */
class WebAppSetting extends AppSetting
{
    /**
     * @var array<string,string>
     */
    protected $resources = [
        // note
        'note' => Note\NoteWebSetting::class,
    ];
}
