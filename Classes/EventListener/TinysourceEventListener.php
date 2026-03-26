<?php
declare(strict_types=1);

namespace NITSAN\NsBasetheme\EventListener;

/*  | This extension is made with ❤ for TYPO3 CMS and is licensed
 *  | under GNU General Public License.
 *  |
 *  | (c) 2023-2024 Armin Vieweg <armin@v.ieweg.de>
 *  |     2023 Benjamin Gries <gries@iwkoeln.de>
 *  |     2023-2024 Joel Mai <mai@iwkoeln.de>
 */
use TYPO3\CMS\Frontend\Event\AfterCacheableContentIsGeneratedEvent;
use NITSAN\NsBasetheme\Tinysource;

// @extensionScannerIgnoreFile
class TinysourceEventListener
{
    private Tinysource $tinysource;

    public function __construct(Tinysource $tinysource)
    {
        $this->tinysource = $tinysource;
    }

    public function __invoke(AfterCacheableContentIsGeneratedEvent $event): void
    {
        $content = $event->getContent();
        $processedContent = $this->tinysource->tinysource(
            $content,
            $event->getRequest()
        );
        $event->setContent($processedContent);
    }
}
