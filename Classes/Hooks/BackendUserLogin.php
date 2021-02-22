<?php

namespace NITSAN\NsBasetheme\Hooks;

use NITSAN\NsBasetheme\Controller\NsBasethemeLicenseModuleController;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook to display verbose information about the felogin plugin
 *
 */
class BackendUserLogin
{
    public function dispatch(array $backendUser)
    {
        $this->nsBasethemeLicenseModule = GeneralUtility::makeInstance(NsBasethemeLicenseModuleController::class);
        $this->nsBasethemeLicenseModule->connectToServer();
    }
}
