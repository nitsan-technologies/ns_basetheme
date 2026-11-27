<?php

declare(strict_types=1);

namespace NITSAN\NsBasetheme\Tests\Unit\Helper;

use NITSAN\NsBasetheme\Helper\ResourceCompressorPath;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Core\ApplicationContext;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

final class ResourceCompressorPathTest extends UnitTestCase
{
    #[Test]
    public function toStringReturnsPathRelativeToPublicRoot(): void
    {
        $publicPath = sys_get_temp_dir() . '/ns_basetheme_compress_' . uniqid('', true);
        mkdir($publicPath, 0775, true);
        Environment::initialize(
            new ApplicationContext('Testing'),
            true,
            false,
            $publicPath,
            $publicPath,
            $publicPath . '/var',
            $publicPath . '/config',
            $publicPath . '/index.php',
            'UNIX'
        );

        $subject = new ResourceCompressorPath();
        $relative = (string)$subject;

        self::assertSame('typo3temp/assets/', $relative);
        self::assertStringNotContainsString($publicPath, $relative);
        self::assertFalse(str_starts_with($relative, '/'));

        rmdir($publicPath);
    }
}
