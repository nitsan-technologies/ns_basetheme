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
        if ($extname !== 'ns_basetheme') {
            return;
        }
        if (version_compare(TYPO3_branch, '9.0', '>')) {
            if (Environment::isComposerMode()) {
                $folder = Environment::getProjectPath() . '/config/sites/ns_basetheme/';
                $sConfig = Environment::getPublicPath() . '/typo3conf/ext/ns_basetheme/sites/ns_basetheme/config.yaml';
                $dConfig = Environment::getProjectPath() . '/config/sites/ns_basetheme/config.yaml';
            } else {
                $folder = Environment::getPublicPath() . '/typo3conf/sites/ns_basetheme/';
                $sConfig = Environment::getPublicPath() . '/typo3conf/ext/ns_basetheme/sites/ns_basetheme/config.yaml';
                $dConfig = Environment::getPublicPath() . '/typo3conf/sites/ns_basetheme/config.yaml';
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
}
