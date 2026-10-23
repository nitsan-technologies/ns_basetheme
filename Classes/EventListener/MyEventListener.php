<?php

declare(strict_types=1);

namespace NITSAN\NsBasetheme\EventListener;

use NITSAN\NsBasetheme\Setup;
use TYPO3\CMS\Core\Package\Event\AfterPackageActivationEvent;

class MyEventListener
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
