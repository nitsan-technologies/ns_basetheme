<?php

declare(strict_types=1);

namespace NITSAN\NsBasetheme\EventListener;

use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Package\Event\AfterPackageActivationEvent;
use TYPO3\CMS\Core\Core\Environment;
#[AsEventListener(
    identifier: 'my-extension/extension-activated',
)]
final class MyEventListener
{
    public function __invoke(AfterPackageActivationEvent $event)
    {
  
        if ($event->getPackageKey()) {
            $this->executeInstall($event);
        }
    }

    private function executeInstall($event): void
    {
      $extname = $event->getPackageKey();

      if (str_contains($extname, 'ns_')   && $extname != 'ns_license' && $extname != 'ns_basetheme') {
        $siteRoot = \TYPO3\CMS\Core\Core\Environment::getPublicPath();

        // Check SQL import file, and rename it
        if (Environment::isComposerMode()) {
            $packageName = str_replace('_', '-', $extname);
        }
       
        $extFolder = (Environment::isComposerMode()) ? Environment::getProjectPath() . '/vendor/nitsan/' . $packageName . '/' : $siteRoot . '/typo3conf/ext/' . $extname . '/';
        if (str_contains($extname, 'ns_theme_')) {
            if (file_exists($extFolder . 'ext_tables_static+adt.sql')) {
                rename($extFolder . 'ext_tables_static+adt.sql', $extFolder . 'ext_tables_static+adt..sql');
            }
        }
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
        $logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Log\LogManager::class)->getLogger(__CLASS__);
            
        // Let's check existing configuration found
        if (!file_exists($dConfig) && file_exists($sConfig)) {

            // If fresh setup then let's create folder structure
            if (is_dir($folder) === false) {
                \TYPO3\CMS\Core\Utility\GeneralUtility::mkdir_deep($folder);
            }
            else {
                $logger->info('Permission error to create site configuration.');
            }
            // Let's clone whole configuration
            if (!copy($sConfig, $dConfig)) {
                $logger->info('Site configuration failed to import.');
            }
            else {
                $logger->info('Site configuration successfully imported.');
            }
        } else {
            $logger->info('Site configuration is already configured.');
        }

    }
}
