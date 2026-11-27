<?php

declare(strict_types=1);

namespace NITSAN\NsBasetheme\Tests\Unit\Middleware;

use NITSAN\NsBasetheme\Middleware\PwaMiddleware;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Core\ApplicationContext;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

final class PwaMiddlewareTest extends UnitTestCase
{
    private string $publicPath = '';

    protected function setUp(): void
    {
        parent::setUp();
        $this->publicPath = sys_get_temp_dir() . '/ns_basetheme_pwa_' . uniqid('', true);
        mkdir($this->publicPath . '/fileadmin', 0775, true);
        Environment::initialize(
            new ApplicationContext('Testing'),
            true,
            false,
            $this->publicPath,
            $this->publicPath,
            $this->publicPath . '/var',
            $this->publicPath . '/config',
            $this->publicPath . '/index.php',
            'UNIX'
        );
    }

    protected function tearDown(): void
    {
        $this->removeDirectory($this->publicPath);
        parent::tearDown();
    }

    #[Test]
    public function prepareJsonDataMapsSiteConfiguration(): void
    {
        $subject = new PwaMiddleware($this->createStub(PageRenderer::class));
        $result = $subject->prepareJsonData([
            'short_name' => 'Demo',
            'name' => 'Demo Site',
            'icon_192' => '/fileadmin/pwa/icon-192.png',
            'icon_192_type' => 'image/png',
            'icon_512' => '/fileadmin/pwa/icon-512.png',
            'icon_512_type' => 'image/png',
            'icon_144' => '/fileadmin/pwa/icon-144.png',
            'icon_144_type' => 'image/png',
            'start_url' => '/',
            'background_color' => '#ffffff',
            'display' => 'standalone',
            'scope' => '/',
            'theme_color' => '#000000',
        ]);

        self::assertSame('Demo', $result['short_name']);
        self::assertSame('Demo Site', $result['name']);
        self::assertSame('/fileadmin/pwa/icon-192.png', $result['icons'][0]['src']);
        self::assertSame('standalone', $result['display']);
    }

    #[Test]
    public function processFilesWritesManifestOnceWhenContentUnchanged(): void
    {
        $subject = new PwaMiddleware($this->createStub(PageRenderer::class));
        $data = [
            'name' => 'Demo',
            'short_name' => 'Demo',
            'icons' => [],
            'start_url' => '/',
            'background_color' => '#fff',
            'display' => 'standalone',
            'scope' => '/',
            'theme_color' => '#000',
        ];
        $payload = (string)json_encode($data);
        $manifestPath = $this->publicPath . '/' . PwaMiddleware::MANIFEST_NAME;

        $subject->processFiles($data);
        self::assertFileExists($manifestPath);

        $frozenMtime = time() - 120;
        touch($manifestPath, $frozenMtime);
        clearstatcache(true, $manifestPath);

        $subject->processFiles($data);
        clearstatcache(true, $manifestPath);

        self::assertSame($frozenMtime, filemtime($manifestPath));
        self::assertSame($payload, (string)file_get_contents($manifestPath));
    }

    #[Test]
    public function processFilesRewritesManifestWhenContentChanged(): void
    {
        $subject = new PwaMiddleware($this->createStub(PageRenderer::class));
        $first = ['name' => 'One', 'icons' => [], 'start_url' => '/', 'short_name' => 'One', 'background_color' => '#fff', 'display' => 'standalone', 'scope' => '/', 'theme_color' => '#000'];
        $second = ['name' => 'Two', 'icons' => [], 'start_url' => '/', 'short_name' => 'Two', 'background_color' => '#fff', 'display' => 'standalone', 'scope' => '/', 'theme_color' => '#000'];
        $manifestPath = $this->publicPath . '/' . PwaMiddleware::MANIFEST_NAME;

        $subject->processFiles($first);
        $subject->processFiles($second);

        self::assertSame((string)json_encode($second), (string)file_get_contents($manifestPath));
    }

    private function removeDirectory(string $directory): void
    {
        if (!is_dir($directory)) {
            return;
        }
        $items = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($items as $item) {
            if ($item->isDir()) {
                rmdir($item->getPathname());
            } else {
                unlink($item->getPathname());
            }
        }
        rmdir($directory);
    }
}
