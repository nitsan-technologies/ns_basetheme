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
    public function executeOnSignal($extname = null)
    {
        if (strpos($extname, 'ns_') !== false && $extname != 'ns_license' && $extname != 'ns_basetheme') {
            $activePackages = GeneralUtility::makeInstance(PackageManager::class)->isPackageActive('ns_license');
            if ($activePackages) {
                $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
                $this->nsLicenseModule = $this->objectManager->get(\NITSAN\NsLicense\Controller\NsLicenseModuleController::class);
                $installed = $this->nsLicenseModule->connectToServer($extname);
                if (strpos($extname, 'ns_theme_') !== false && $installed) {
                    if (version_compare(TYPO3_branch, '9.0', '>')) {
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
                                GeneralUtility::mkdir_deep($folder);
                            }
                            if (!copy($sConfig, $dConfig)) {
                                // File Already Exist
                                $this->logger->info('Site Configuration is not copied.');
                            }
                        } else {
                            $this->logger->info('Site Configuration is already configured.');
                        }
                    }
                }
            } else {
                if (version_compare(TYPO3_branch, '9.0', '>')) {
                    $this->siteRoot = \TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/';
                } else {
                    $this->siteRoot = PATH_site;
                }
                $extFolder = $this->siteRoot . '/typo3conf/ext/' . $extension . '/';
                $this->updateFiles($extFolder, $extname);
            }
        }
    }

    /**
     * updateFiles
     *
     * @return void
     */
    public function updateFiles($extFolder, $extension)
    {
        if (file_exists($extFolder . 'ext_tables.php')) {
            rename($extFolder . 'ext_tables.php', $extFolder . 'copy_ext_tables.txt');
        }
        if (file_exists($extFolder . 'ext_localconf.php')) {
            rename($extFolder . 'ext_localconf.php', $extFolder . 'copy_ext_localconf.txt');
        }
        if (file_exists($extFolder . 'Configuration/TCA/Overrides/sys_template.php')) {
            rename($extFolder . 'Configuration/TCA/Overrides/sys_template.php', $extFolder . 'Configuration/TCA/Overrides/copy_sys_template.txt');
        }
        try {
            $this->unloadExtension($extension);
        } catch (\Exception $e) {
            $this->addFlashMessage($e->getMessage(), $extension, \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        }
    }

    /**
     * Wrapper function for unloading extensions
     *
     * @param string $extensionKey
     */
    protected function unloadExtension($extensionKey)
    {
        $this->packageManager = GeneralUtility::makeInstance(PackageManager::class);
        $this->cacheManager = GeneralUtility::makeInstance(CacheManager::class);
        $this->packageManager->deactivatePackage($extensionKey);
        $this->cacheManager->flushCachesInGroup('system');
    }
}
