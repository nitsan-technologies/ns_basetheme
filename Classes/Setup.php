<?php

declare(strict_types=1);

namespace NITSAN\NsBasetheme;

use NITSAN\NsLicense\Service\LicenseService;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Handles post-activation / site-import setup for NITSAN packages.
 *
 * Invoked from two distinct lifecycle events (both required):
 * - AfterPackageActivationEvent (PackageActivationSiteImportListener)
 * - AfterExtensionSiteFilesHaveBeenImportedEvent (this class tagged in Services.yaml)
 *
 * Site import is idempotent: existing destination config skips a second copy.
 */
class Setup
{
    protected string $siteRoot = '';

    protected LoggerInterface $logger;

    public function __construct(
        private readonly PackageManager $packageManager,
        private readonly LicenseService $licenseService,
        LogManager $logManager,
    ) {
        $this->logger = $logManager->getLogger(self::class);
    }

    public function executeOnSignalAfter(mixed $extname = null): void
    {
        if (is_object($extname) && method_exists($extname, 'getPackageKey')) {
            $extname = $extname->getPackageKey();
        }

        $extname = (string)$extname;
        if ($extname === '' || !$this->shouldHandleExtension($extname)) {
            return;
        }

        $this->siteRoot = Environment::getPublicPath();
        $packageName = str_replace('_', '-', $extname);
        $extFolder = Environment::isComposerMode()
            ? Environment::getProjectPath() . '/vendor/nitsan/' . $packageName . '/'
            : $this->siteRoot . '/typo3conf/ext/' . $extname . '/';

        if (str_contains($extname, 'ns_theme_')) {
            $sqlFile = $extFolder . 'ext_tables_static+adt.sql';
            if (file_exists($sqlFile)) {
                rename($sqlFile, $extFolder . 'ext_tables_static+adt..sql');
            }
        }

        if ($this->packageManager->isPackageActive('ns_license') && str_contains($extname, 'ns_theme_')) {
            $this->licenseService->connectToServer($extname, 1, 'checkTheme');
        }

        $this->importSiteConfiguration($extname);
    }

    public function shouldHandleExtension(string $extname): bool
    {
        return str_contains($extname, 'ns_')
            && $extname !== 'ns_license'
            && $extname !== 'ns_basetheme';
    }

    protected function importSiteConfiguration(string $extname): void
    {
        $siteKeyConfig = is_dir(Environment::getPublicPath() . '/typo3conf/ext/' . $extname . '/Initialisation/Site/ns_basetheme/')
            ? 'ns_basetheme'
            : $extname;

        if (Environment::isComposerMode()) {
            $folder = Environment::getProjectPath() . '/config/sites/' . $siteKeyConfig . '/';
            $composerKey = str_replace('_', '-', $siteKeyConfig);
            $sConfig = Environment::getProjectPath() . '/vendor/nitsan/' . $composerKey . '/Initialisation/Site/' . $siteKeyConfig . '/config.yaml';
            $dConfig = Environment::getProjectPath() . '/config/sites/' . $siteKeyConfig . '/config.yaml';
        } else {
            $folder = Environment::getPublicPath() . '/typo3conf/sites/' . $siteKeyConfig . '/';
            $sConfig = Environment::getPublicPath() . '/typo3conf/ext/' . $extname . '/Initialisation/Site/' . $siteKeyConfig . '/config.yaml';
            $dConfig = Environment::getPublicPath() . '/typo3conf/sites/' . $siteKeyConfig . '/config.yaml';
        }

        if (file_exists($dConfig) || !file_exists($sConfig)) {
            $this->logger->info('Site configuration is already configured.');
            return;
        }

        if (!is_dir($folder)) {
            GeneralUtility::mkdir_deep($folder);
        } else {
            $this->logger->info('Permission error to create site configuration.');
        }

        if (!copy($sConfig, $dConfig)) {
            $this->logger->info('Site configuration failed to import.');
            return;
        }

        $this->logger->info('Site configuration successfully imported.');
    }
}
