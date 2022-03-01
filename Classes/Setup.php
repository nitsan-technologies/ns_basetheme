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
            $activePackages = GeneralUtility::makeInstance(PackageManager::class)->isPackageActive('ns_license');
            if ($activePackages && strpos($extname, 'ns_theme_') !== false) {
                $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
                $this->nsLicenseModule = $this->objectManager->get(\NITSAN\NsLicense\Controller\NsLicenseModuleController::class);
                $installed = $this->nsLicenseModule->connectToServer($extname);
                if (!$installed) {
                    $this->updateFiles($extFolder, $extname);
                }
            } else {
                if (strpos($extname, 'ns_theme_') !== false) {
                    $licenseData = $this->fetchLicense('domain=' . GeneralUtility::getIndpEnv('HTTP_HOST') . '&ns_key=' . $extname);
                    if ($licenseData->status) {
                        $extFolder = $this->siteRoot . '/typo3conf/ext/' . $extname . '/';
                        $this->updateFiles($extFolder, $extname);
                    }
                }
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
                $this->siteRoot = \TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/';
            } else {
                $this->siteRoot = PATH_site;
            }
            if (strpos($extname, 'ns_theme_') !== false && version_compare(TYPO3_branch, '9.0', '>')) {
                
                // Check SQL import file, and rename it
                $extFolder = $this->siteRoot . '/typo3conf/ext/' . $extname . '/';
                if (file_exists($extFolder . 'ext_tables_static+adt.sql')) {
                    rename($extFolder . 'ext_tables_static+adt.sql', $extFolder . 'ext_tables_static+adt..sql');
                }
            }
        }
    }

    /**
     * fetchLicense
     * @param string $license.
     *
     * @return array|null
     **/
    public function fetchLicense($license)
    {
        $url = 'https://composer.t3terminal.com/API/GetComposerDetails.php?' . $license;
        $curl = curl_init();
        curl_setopt_array($curl, [
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
        ]);
        $response = curl_exec($curl);
        if (!$response) {
            echo 'Error :- ' . curl_error($curl);
        }
        curl_close($curl);

        return json_decode($response);
    }

    /**
     * updateFiles
     *
     * @return void
     */
    public function updateFiles($extFolder, $extension)
    {
        if (file_exists($extFolder . 'ext_tables.php')) {
            rename($extFolder . 'ext_tables.php', $extFolder . 'ext_tables..php');
        }
        if (file_exists($extFolder . 'Configuration/TCA/Overrides/sys_template.php')) {
            rename($extFolder . 'Configuration/TCA/Overrides/sys_template.php', $extFolder . 'Configuration/TCA/Overrides/sys_template..php');
        }
        if (file_exists($extFolder . 'Configuration')) {
            rename($extFolder . 'Configuration', $extFolder . 'Configuration.');
        }
        if (file_exists($extFolder . 'Resources')) {
            rename($extFolder . 'Resources', $extFolder . 'Resources.');
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
