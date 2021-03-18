<?php

namespace NITSAN\NsBasetheme\Hooks;

use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook to display verbose information about the felogin plugin
 *
 */
class BackendUserLogin
{
    public function dispatch(array $backendUser)
    {
        $activePackages = GeneralUtility::makeInstance(PackageManager::class)->isPackageActive('ns_license');
        if ($activePackages) {
            $this->nsLicenseModule = GeneralUtility::makeInstance(\NITSAN\NsLicense\Controller\NsLicenseModuleController::class);
            $this->nsLicenseModule->connectToServer();
        } else {
            if (version_compare(TYPO3_branch, '9.0', '>')) {
                $this->siteRoot = \TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/';
            } else {
                $this->siteRoot = PATH_site;
            }
            $this->setup = GeneralUtility::makeInstance(\NITSAN\NsBasetheme\Setup::class);
            $activePackages = GeneralUtility::makeInstance(PackageManager::class)->getActivePackages();
            $allExtensions = [];
            foreach ($activePackages as $key => $value) {
                $exp_key = explode('_theme', $key);
                if ($exp_key[0] == 'ns') {
                    if ($key != 'ns_basetheme' && $key != 'ns_license') {
                        $extFolder = $this->siteRoot . '/typo3conf/ext/' . $key . '/';
                        $this->setup->updateFiles($extFolder, $key);
                    }
                }
            }
        }
    }
}
