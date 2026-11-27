<?php

declare(strict_types=1);

namespace NITSAN\NsBasetheme\Hooks;

use NITSAN\NsLicense\Service\LicenseService;
use TYPO3\CMS\Core\Authentication\Event\AfterUserLoggedInEvent;
use TYPO3\CMS\Core\Package\PackageManager;

class BackendUserLogin
{
    public function __construct(
        private readonly PackageManager $packageManager,
        private readonly LicenseService $licenseService,
    ) {}

    public function dispatch(AfterUserLoggedInEvent $backendUser): void
    {
        // @extensionScannerIgnoreLine
        if (!$this->packageManager->isPackageActive('ns_license')) {
            return;
        }

        // @extensionScannerIgnoreLine
        $activePackages = $this->packageManager->getAvailablePackages();
        foreach ($activePackages as $key => $value) {
            $exp_key = explode('_theme', $key);
            if ($exp_key[0] == 'ns') {
                if ($key != 'ns_basetheme' && $key != 'ns_license') {
                    $this->licenseService->connectToServer($key, 0, 'checkTheme');
                }
            }
        }
    }
}
