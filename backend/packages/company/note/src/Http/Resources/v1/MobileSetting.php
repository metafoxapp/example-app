<?php
/**
 * @author  developer@phpfox.com
 * @license phpfox.com
 */

namespace Company\Note\Http\Resources\v1;

use MetaFox\Platform\Support\Resource\MobileAppSetting as Setting;

/**
 *--------------------------------------------------------------------------
 * Mobile Resource Config Gateway
 * Inject this class to @link \Company\Note\Listeners\PackageSettingListener::getMobileAppSettings()
 *--------------------------------------------------------------------------
 * stub: /packages/resources/app_setting.stub.
 */

/**
 * Class MobileSetting.
 * @ignore
 * @codeCoverageIgnore
 */
class MobileSetting extends Setting
{
    /**
     * @var array<string,string>
     */
    protected $resources = [
        // note
        // 'country_admin' => Note\MobileSetting::class,
    ];
}
