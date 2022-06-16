<?php

namespace NITSAN\NsBasetheme\Hooks;

use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 *
 *
 */
class BackendUserLogin
{
    public function dispatch(array $backendUser)
    {
        if($backendUser['user']['admin'] == 1) {
            // Let's check license system
            $isLicenseActivate = GeneralUtility::makeInstance(PackageManager::class)->isPackageActive('ns_license');
            $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);

            if ($isLicenseActivate) {
                $this->nsLicenseModule = $this->objectManager->get(\NITSAN\NsLicense\Controller\NsLicenseModuleController::class);
                $activePackages = GeneralUtility::makeInstance(PackageManager::class)->getAvailablePackages();
                foreach ($activePackages as $key => $value) {
                    $exp_key = explode('_theme', $key);
                    if ($exp_key[0] == 'ns') {
                        if ($key != 'ns_basetheme' && $key != 'ns_license') {
                            $this->nsLicenseModule->connectToServer($key, 0);
                        }
                    }
                }
            }
        }
    }
}
