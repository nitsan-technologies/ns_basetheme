<?php

declare(strict_types=1);

namespace NITSAN\NsBasetheme\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PwaMiddleware implements MiddlewareInterface
{
    public const MANIFEST_NAME = 'site.webmanifest';

    protected ServerRequestInterface $request;

    public function __construct(
        private readonly PageRenderer $pageRenderer,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->request = $request;
        $this->processPwa();

        return $handler->handle($request);
    }

    protected function processPwa(): void
    {
        $configurations = $this->getConfigurations();
        if (($configurations['enabled'] ?? false) !== true && ($configurations['enabled'] ?? false) !== '1') {
            return;
        }

        $manifestData = $this->prepareJsonData($configurations);
        $this->addHeaderData($configurations, $manifestData);
        $this->processFiles($manifestData);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getConfigurations(): array
    {
        $site = $this->request->getAttribute('site');
        if (!$site instanceof Site) {
            return [];
        }

        return $site->getConfiguration();
    }

    /**
     * @param array<string, mixed> $configurations
     * @param array<string, mixed> $manifestData
     */
    protected function addHeaderData(array $configurations, array $manifestData): void
    {
        $normalizedParams = $this->request->getAttribute('normalizedParams');
        if ($normalizedParams === null) {
            return;
        }

        $siteUrl = $normalizedParams->getSiteUrl();
        // Stable cache-buster: changes only when manifest content changes (not every request).
        $manifestUrl = $siteUrl . self::MANIFEST_NAME . '?' . substr(hash('sha256', (string)json_encode($manifestData)), 0, 12);
        $configurationsName = htmlspecialchars((string)($configurations['name'] ?? ''));
        $configurationsIcon = $this->sanitizePublicUrl((string)($configurations['icon'] ?? ''));
        $configurationsColor = htmlspecialchars((string)($configurations['theme_color'] ?? ''));

        $headerData = "<link rel='manifest' href='" . htmlspecialchars($manifestUrl) . "'>";
        $headerData .= '<meta name="apple-mobile-web-app-capable" content="yes">';
        $headerData .= '<meta name="apple-mobile-web-app-status-bar-style" content="black">';
        $headerData .= "<meta name='apple-mobile-web-app-title' content='{$configurationsName}'>";
        if ($configurationsIcon !== '') {
            $headerData .= "<link rel='apple-touch-icon' href='{$configurationsIcon}'>";
            $headerData .= "<meta name='msapplication-TileImage' content='{$configurationsIcon}'>";
        }
        $headerData .= "<meta name='theme-color' content='{$configurationsColor}'>";
        $headerData .= "<meta name='msapplication-TileColor' content='{$configurationsColor}'>";
        $this->pageRenderer->addHeaderData($headerData);
    }

    /**
     * @param array<string, mixed> $configurations
     * @return array<string, mixed>
     */
    public function prepareJsonData(array $configurations): array
    {
        $data = [
            'short_name' => (string)($configurations['short_name'] ?? ''),
            'name' => (string)($configurations['name'] ?? ''),
            'icons' => [
                [
                    'src' => $this->sanitizePublicUrl((string)($configurations['icon_192'] ?? '')),
                    'sizes' => '192x192',
                    'type' => (string)($configurations['icon_192_type'] ?? ''),
                    'density' => 4,
                ],
                [
                    'src' => $this->sanitizePublicUrl((string)($configurations['icon_512'] ?? '')),
                    'sizes' => '512x512',
                    'type' => (string)($configurations['icon_512_type'] ?? ''),
                ],
                [
                    'src' => $this->sanitizePublicUrl((string)($configurations['icon_144'] ?? '')),
                    'sizes' => '144x144',
                    'type' => (string)($configurations['icon_144_type'] ?? ''),
                    'purpose' => 'maskable',
                ],
            ],
            'start_url' => (string)($configurations['start_url'] ?? ''),
            'background_color' => (string)($configurations['background_color'] ?? ''),
            'display' => (string)($configurations['display'] ?? ''),
            'scope' => (string)($configurations['scope'] ?? ''),
            'theme_color' => (string)($configurations['theme_color'] ?? ''),
        ];

        if (!empty($configurations['ss_icon_desktop'])) {
            $data['screenshots'][] = [
                'src' => $this->sanitizePublicUrl((string)$configurations['ss_icon_desktop']),
                'sizes' => (string)($configurations['ss_icon_size_desktop'] ?? ''),
                'type' => (string)($configurations['ss_icon_desktop_type'] ?? ''),
                'form_factor' => 'wide',
                'label' => 'For Desktop',
            ];
        }
        if (!empty($configurations['ss_icon_mobile'])) {
            $data['screenshots'][] = [
                'src' => $this->sanitizePublicUrl((string)$configurations['ss_icon_mobile']),
                'sizes' => (string)($configurations['ss_icon_size_mobile'] ?? ''),
                'type' => (string)($configurations['ss_icon_mobile_type'] ?? ''),
                'form_factor' => 'narrow',
                'label' => 'For Mobile',
            ];
        }

        return $data;
    }

    /**
     * Writes manifest and default icons only when missing or content changed.
     *
     * @param array<string, mixed> $data
     */
    public function processFiles(array $data): void
    {
        $pwaFileadminPath = Environment::getPublicPath() . '/fileadmin/pwa';
        if (!is_dir($pwaFileadminPath) && !GeneralUtility::mkdir($pwaFileadminPath) && !is_dir($pwaFileadminPath)) {
            return;
        }

        $iconSource = Environment::isComposerMode()
            ? Environment::getProjectPath() . '/vendor/nitsan/ns-basetheme/Resources/Public/pwa/icons/'
            : Environment::getPublicPath() . '/typo3conf/ext/ns_basetheme/Resources/Public/pwa/icons/';
        $this->copyfolder($iconSource, $pwaFileadminPath . '/');

        $jsonFile = Environment::getPublicPath() . '/' . self::MANIFEST_NAME;
        $encoded = (string)json_encode($data);
        if (!$this->manifestNeedsUpdate($jsonFile, $encoded)) {
            return;
        }
        GeneralUtility::writeFile($jsonFile, $encoded);
    }

    public function manifestNeedsUpdate(string $jsonFile, string $encoded): bool
    {
        if (!is_file($jsonFile)) {
            return true;
        }
        $existing = @file_get_contents($jsonFile);
        return $existing === false || $existing !== $encoded;
    }

    /**
     * Copy default PWA icons only when the destination file is missing.
     * Preserves any custom files already present under fileadmin/pwa/.
     */
    public function copyfolder(string $from, string $to, string $ext = '*'): void
    {
        if (!is_dir($from)) {
            return;
        }
        if (!is_dir($to) && !@mkdir($to, 0775, true) && !is_dir($to)) {
            return;
        }

        $all = glob($from . $ext, GLOB_MARK) ?: [];
        foreach ($all as $entry) {
            $name = basename($entry);
            if (is_dir($entry)) {
                $this->copyfolder($from . $name . '/', $to . $name . '/');
                continue;
            }
            $target = $to . $name;
            if (is_file($target)) {
                continue;
            }
            @copy($entry, $target);
        }
    }

    /**
     * Allow relative and http(s) URLs used by existing site configs; block script schemes.
     */
    protected function sanitizePublicUrl(string $url): string
    {
        $url = trim($url);
        if ($url === '' || preg_match('#^(javascript|data|vbscript):#i', $url) === 1) {
            return '';
        }

        return htmlspecialchars($url);
    }
}
