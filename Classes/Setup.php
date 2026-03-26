<?php
declare(strict_types=1);

namespace NITSAN\NsBasetheme;
/**
 * This Class called when Importing database of Templates
 */
use NITSAN\NsLicense\Service\LicenseService;
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

    protected $logger;

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
            if (Environment::isComposerMode()) {
                $packageName = str_replace('_', '-', $extname);
            }
           
            $extFolder = (Environment::isComposerMode()) ? Environment::getProjectPath() . '/vendor/nitsan/' . $packageName . '/' : $this->siteRoot . '/typo3conf/ext/' . $extname . '/';
            if (str_contains($extname, 'ns_theme_')) {
                if (file_exists($extFolder . 'ext_tables_static+adt.sql')) {
                    rename($extFolder . 'ext_tables_static+adt.sql', $extFolder . 'ext_tables_static+adt..sql');
                }
            }

            // Let's check license system
            // @extensionScannerIgnoreLine
            $activePackages = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Package\PackageManager::class)->getActivePackages();
            $isLicenseCheck = false;
            foreach ($activePackages as $key => $value) {
                if ($key == 'ns_license') {
                    $isLicenseCheck = true;
                }
            }
            if ($isLicenseCheck && str_contains($extname, 'ns_theme_')) {
                $nsLicenseModule = GeneralUtility::makeInstance(LicenseService::class);
                $nsLicenseModule->connectToServer($extname, 1, 'checkTheme');
            }

             // Check if site config == ns_basetheme
             if(is_dir(Environment::getPublicPath() . '/typo3conf/ext/' . $extname . '/Initialisation/Site/ns_basetheme/') === true) {
                $siteKeyConfig = 'ns_basetheme';
            }
            else {
                $siteKeyConfig = $extname;
            }
            if (Environment::isComposerMode()) {
                $folder = Environment::getProjectPath() . '/config/sites/' . $siteKeyConfig . '/';
                $extname = str_replace('_','-',$siteKeyConfig);
                $sConfig = Environment::getProjectPath() . '/vendor/nitsan/' . $extname . '/Initialisation/Site/' . $siteKeyConfig . '/config.yaml';
                $dConfig = Environment::getProjectPath() . '/config/sites/' . $siteKeyConfig . '/config.yaml';
            } else {
                $folder = Environment::getPublicPath() . '/typo3conf/sites/' . $siteKeyConfig . '/';
                $sConfig = Environment::getPublicPath() . '/typo3conf/ext/' . $extname . '/Initialisation/Site/' . $siteKeyConfig . '/config.yaml';
                $dConfig = Environment::getPublicPath() . '/typo3conf/sites/' . $siteKeyConfig . '/config.yaml';
            }
            // Logger configuration
            $this->logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Log\LogManager::class)->getLogger(__CLASS__);
            
            // Let's check existing configuration found
            if (!file_exists($dConfig) && file_exists($sConfig)) {

                // If fresh setup then let's create folder structure
                if (is_dir($folder) === false) {
                    \TYPO3\CMS\Core\Utility\GeneralUtility::mkdir_deep($folder);
                }
                else {
                    $this->logger->info('Permission error to create site configuration.');
                }
                // Let's clone whole configuration
                if (!copy($sConfig, $dConfig)) {
                    $this->logger->info('Site configuration failed to import.');
                }
                else {
                    $this->logger->info('Site configuration successfully imported.');
                }
            } else {
                $this->logger->info('Site configuration is already configured.');
            }

        }
    }
}
