<?php
namespace NITSAN\NsBasetheme;
/**
 * This Class called when Importing database of Templates
 */
use NITSAN\NsLicense\Controller\NsLicenseModuleController;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Setup
 */
class Setup
{
    /**
     * @var string
     */
    protected string $siteRoot;
    
   /**
     * executeOnSignalAfter
     */
    public function executeOnSignalAfter($extname = null)
    {
        if (is_object($extname)) {
            $extname = $extname->getPackageKey();
        }
        if (str_contains($extname, 'ns_')   && $extname != 'ns_license' && $extname != 'ns_basetheme') {
            $this->siteRoot = \TYPO3\CMS\Core\Core\Environment::getPublicPath();

            // Check SQL import file, and rename it
            $extFolder = (Environment::isComposerMode()) ? Environment::getProjectPath() . '/extensions/' . $extname . '/' : $this->siteRoot . '/typo3conf/ext/' . $extname . '/';
            if (file_exists($extFolder . 'ext_tables_static+adt.sql')) {
                rename($extFolder . 'ext_tables_static+adt.sql', $extFolder . 'ext_tables_static+adt..sql');
            }

            // Let's check license system
            $activePackages = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Package\PackageManager::class)->getActivePackages();
            $isLicenseCheck = false;
            foreach ($activePackages as $key => $value) {
                if ($key == 'ns_license') {
                    $isLicenseCheck = true;
                }
            }
            if ($isLicenseCheck && str_contains($extname, 'ns_theme_')) {
                $nsLicenseModule = GeneralUtility::makeInstance(NsLicenseModuleController::class);
                $nsLicenseModule->connectToServer($extname, 0);
            }
        }
    }
}
