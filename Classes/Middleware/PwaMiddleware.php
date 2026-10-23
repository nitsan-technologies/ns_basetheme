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

        $this->addHeaderData($configurations);
        $this->processFiles($this->prepareJsonData($configurations));
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
     */
    protected function addHeaderData(array $configurations): void
    {
        $normalizedParams = $this->request->getAttribute('normalizedParams');
        if ($normalizedParams === null) {
            return;
        }

        $siteUrl = $normalizedParams->getSiteUrl();
        $manifestUrl = $siteUrl . self::MANIFEST_NAME . '?' . time();
        $configurationsName = htmlspecialchars((string)($configurations['name'] ?? ''));
        $configurationsIcon = htmlspecialchars((string)($configurations['icon'] ?? ''));
        $configurationsColor = htmlspecialchars((string)($configurations['theme_color'] ?? ''));

        $headerData = "<link rel='manifest' href='" . htmlspecialchars($manifestUrl) . "'>";
        $headerData .= '<meta name="apple-mobile-web-app-capable" content="yes">';
        $headerData .= '<meta name="apple-mobile-web-app-status-bar-style" content="black">';
        $headerData .= "<meta name='apple-mobile-web-app-title' content='{$configurationsName}'>";
        $headerData .= "<link rel='apple-touch-icon' href='{$configurationsIcon}'>";
        $headerData .= "<meta name='msapplication-TileImage' content='{$configurationsIcon}'>";
        $headerData .= "<meta name='theme-color' content='{$configurationsColor}'>";
        $headerData .= "<meta name='msapplication-TileColor' content='{$configurationsColor}'>";
        GeneralUtility::makeInstance(PageRenderer::class)->addHeaderData($headerData);
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
                    'src' => (string)($configurations['icon_192'] ?? ''),
                    'sizes' => '192x192',
                    'type' => (string)($configurations['icon_192_type'] ?? ''),
                    'density' => 4,
                ],
                [
                    'src' => (string)($configurations['icon_512'] ?? ''),
                    'sizes' => '512x512',
                    'type' => (string)($configurations['icon_512_type'] ?? ''),
                ],
                [
                    'src' => (string)($configurations['icon_144'] ?? ''),
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
                'src' => (string)$configurations['ss_icon_desktop'],
                'sizes' => (string)($configurations['ss_icon_size_desktop'] ?? ''),
                'type' => (string)($configurations['ss_icon_desktop_type'] ?? ''),
                'form_factor' => 'wide',
                'label' => 'For Desktop',
            ];
        }
        if (!empty($configurations['ss_icon_mobile'])) {
            $data['screenshots'][] = [
                'src' => (string)$configurations['ss_icon_mobile'],
                'sizes' => (string)($configurations['ss_icon_size_mobile'] ?? ''),
                'type' => (string)($configurations['ss_icon_mobile_type'] ?? ''),
                'form_factor' => 'narrow',
                'label' => 'For Mobile',
            ];
        }

        return $data;
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function processFiles(array $data): void
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
        GeneralUtility::writeFile($jsonFile, (string)json_encode($data));
    }

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
            @copy($entry, $to . $name);
        }
    }
}
