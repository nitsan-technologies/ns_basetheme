<?php
declare(strict_types=1);

namespace NITSAN\NsBasetheme\EventListener;

use NITSAN\NsBasetheme\Tinysource as TinysourceService;
use TYPO3\CMS\Frontend\Event\AfterCacheableContentIsGeneratedEvent;

/**
 * Event listener that delegates to Tinysource service (v14 compatible).
 */
class Tinysource
{
    public function __construct(
        private readonly TinysourceService $tinysourceService
    ) {}

    public function __invoke(AfterCacheableContentIsGeneratedEvent $event): void
    {
        $content = $event->getContent();
        $processedContent = $this->tinysourceService->tinysource($content, $event->getRequest());
        $event->setContent($processedContent);
    }
}
