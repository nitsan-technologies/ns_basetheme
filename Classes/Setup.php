<?php

namespace NITSAN\NsBasetheme;

use TYPO3\CMS\Core\Core\Environment;

/**
 * Setup
 */
class Setup
{
    public function executeOnSignal($extname = null)
    {
        if (strpos($extname, 'ns_theme_') !== false) {
            if (version_compare(TYPO3_branch, '9.0', '>') && version_compare(TYPO3_branch, '10.1', '<')) {
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
                if (!file_exists($dConfig)) {
                    if (is_dir($folder) === false) {
                        // Make directory
                        mkdir($folder, 0775, true);
                    }
                    if (!copy($sConfig, $dConfig)) {
                        // File Already Exist
                        $this->logger->info('Site Configuration is not copied.');
                    }
                } else {
                    $this->logger->info('Site Configuration is already configured.');
                }
            } else {
                return;
            }
        }
        return;
    }
}
