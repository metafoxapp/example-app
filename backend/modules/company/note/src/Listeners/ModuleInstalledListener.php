<?php

namespace Company\Note\Listeners;

use MetaFox\Platform\PackageManager;

class ModuleInstalledListener
{
    public function handle(): void
    {
        PackageManager::copyAssets('company/note');
    }
}
