<?php

declare(strict_types=1);

namespace NITSAN\NsBasetheme\EventListener;

use NITSAN\NsBasetheme\Setup;
use TYPO3\CMS\Core\Package\Event\AfterPackageActivationEvent;

/**
 * Runs site-import / license checks when a NITSAN package is activated.
 * Complements Setup's AfterExtensionSiteFilesHaveBeenImportedEvent listener;
 * import itself is idempotent.
 */
class PackageActivationSiteImportListener
{
    public function __construct(private readonly Setup $setup)
    {
    }

    public function __invoke(AfterPackageActivationEvent $event): void
    {
        $packageKey = $event->getPackageKey();
        if ($packageKey === '') {
            return;
        }

        $this->setup->executeOnSignalAfter($packageKey);
    }
}
