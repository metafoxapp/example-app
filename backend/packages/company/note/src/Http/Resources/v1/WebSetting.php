<?php
/**
 * @author  developer@phpfox.com
 * @license phpfox.com
 */

namespace Company\Note\Http\Resources\v1;

use MetaFox\Platform\Support\Resource\WebAppSetting as Setting;

/**
 *--------------------------------------------------------------------------
 * Web Resource Config Gateway
 * Inject this class to @link \Company\Note\Listeners\PackageSettingListener::getWebAppSettings()
 *--------------------------------------------------------------------------
 * stub: /packages/resources/app_setting.stub.
 */

/**
 * Class WebAppSetting.
 * @ignore
 * @codeCoverageIgnore
 */
class WebSetting extends Setting
{
    /**
     * @var array<string,string>
     */
    protected $resources = [
        // note
        'note' => Note\WebSetting::class,
    ];
}
