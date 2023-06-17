<?php


namespace NITSAN\NsBasetheme\Hooks;

use NITSAN\NsLicense\Controller\NsLicenseModuleController;
use TYPO3\CMS\Core\Authentication\Event\AfterUserLoggedInEvent;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class BackendUserLogin
{
    /**
     * @param AfterUserLoggedInEvent $backendUser
     */
    public function dispatch(AfterUserLoggedInEvent $backendUser): void
    {
        $isLicenseActivate = GeneralUtility::makeInstance(PackageManager::class)->isPackageActive('ns_license');
        if ($isLicenseActivate) {
            $activePackages = GeneralUtility::makeInstance(PackageManager::class)->getAvailablePackages();
            $nsLicenseModule = GeneralUtility::makeInstance(NsLicenseModuleController::class);
            foreach ($activePackages as $key => $value) {
                $exp_key = explode('_theme', $key);
                if ($exp_key[0] == 'ns') {
                    if ($key != 'ns_basetheme' && $key != 'ns_license') {
                        $nsLicenseModule->connectToServer($key, 0, 'checkTheme');
                    }
                }
            }
        }
    }
}
