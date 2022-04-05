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
     * executeOnSignal
     *
     * @return void
     */
    public function executeOnSignal($extname = null)
    {
        if (is_object($extname) && $extname instanceof \TYPO3\CMS\Core\Package\Event\BeforePackageActivationEvent) {
			$extname = array_key_first($extname->getPackageKeys());
		}
        
        if (strpos($extname, 'ns_') !== false && $extname != 'ns_license' && $extname != 'ns_basetheme') {
            if (version_compare(TYPO3_branch, '9.0', '>')) {
                $this->siteRoot = \TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/';
            } else {
                $this->siteRoot = PATH_site;
            }
            if (strpos($extname, 'ns_theme_') !== false && version_compare(TYPO3_branch, '9.0', '>')) {
                if (Environment::isComposerMode()) {
                    $folder = Environment::getProjectPath() . '/config/sites/' . $extname . '/';
                    $sConfig = Environment::getPublicPath() . '/typo3conf/ext/' . $extname . '/Initialisation/Site/' . $extname . '/config.yaml';
                    $dConfig = Environment::getProjectPath() . '/config/sites/' . $extname . '/config.yaml';
                } else {
                    $folder = Environment::getPublicPath() . '/typo3conf/sites/' . $extname . '/';
                    $sConfig = Environment::getPublicPath() . '/typo3conf/ext/' . $extname . '/Initialisation/Site/' . $extname . '/config.yaml';
                    $dConfig = Environment::getPublicPath() . '/typo3conf/sites/' . $extname . '/config.yaml';
                }
                // Logger configuration
                $this->logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Log\LogManager::class)->getLogger(__CLASS__);
                if (!file_exists($dConfig) && file_exists($sConfig)) {
                    if (is_dir($folder) === false) {
                        // Make directory
                        \TYPO3\CMS\Core\Utility\GeneralUtility::mkdir_deep($folder);
                    }
                    if (!copy($sConfig, $dConfig)) {
                        // File Already Exist
                        $this->logger->info('Site Configuration is not copied.');
                    }
                } else {
                    $this->logger->info('Site Configuration is already configured.');
                }
            }

            // Let's check license system
            $activePackages = GeneralUtility::makeInstance(PackageManager::class)->isPackageActive('ns_license');
            $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
            $this->nsLicenseModule = $this->objectManager->get(\NITSAN\NsLicense\Controller\NsLicenseModuleController::class);
            if ($activePackages && strpos($extname, 'ns_theme_') !== false) {
                $this->nsLicenseModule->connectToServer($extname);
            }
        }
    }

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
                
                // Check SQL import file, and rename it
                $extFolder = (Environment::isComposerMode()) ? $this->siteRoot . '/extensions/' . $extname . '/' : $this->siteRoot . '/typo3conf/ext/' . $extname . '/';
                if (file_exists($extFolder . 'ext_tables_static+adt.sql')) {
                    rename($extFolder . 'ext_tables_static+adt.sql', $extFolder . 'ext_tables_static+adt..sql');
                }
            }
        }
    }
}
