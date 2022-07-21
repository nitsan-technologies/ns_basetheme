<?php

namespace NITSAN\NsBasetheme;

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Setup
 */
class Setup
{
   /**
     * executeOnSignalAfter
     *
     * @return void
     */
    public function executeOnSignalAfter($extname = null)
    {
        if (is_object($extname)) {
			$extname = $extname->getPackageKey();
		}
        
        if (strpos($extname, 'ns_') !== false && $extname != 'ns_license' && $extname != 'ns_basetheme') {
            if (version_compare(TYPO3_branch, '9.0', '>')) {
                $this->siteRoot = \TYPO3\CMS\Core\Core\Environment::getPublicPath();
            } else {
                $this->siteRoot = PATH_site;
            }
            if (strpos($extname, 'ns_theme_') !== false && version_compare(TYPO3_branch, '9.0', '>')) {
                
                // Check if site config == ns_basetheme
                if(is_dir(Environment::getPublicPath() . '/typo3conf/ext/' . $extname . '/Initialisation/Site/ns_basetheme/') === true) {
                    $siteKeyConfig = 'ns_basetheme';
                }
                else {
                    $siteKeyConfig = $extname;
                }
                if (Environment::isComposerMode()) {
                    $folder = Environment::getProjectPath() . '/config/sites/' . $siteKeyConfig . '/';
                    $sConfig = Environment::getPublicPath() . '/typo3conf/ext/' . $extname . '/Initialisation/Site/' . $siteKeyConfig . '/config.yaml';
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
            if($isLicenseCheck && strpos($extname, 'ns_theme_') !== false) {
                $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
                $this->nsLicenseModule = $this->objectManager->get(\NITSAN\NsLicense\Controller\NsLicenseModuleController::class);
                $this->nsLicenseModule->connectToServer($extname, 0);
            }
        }
    }
}
