<?php

declare(strict_types=1);

namespace NITSAN\NsBasetheme\Tests\Unit;

use NITSAN\NsBasetheme\Tinysource;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

final class TinysourceTest extends UnitTestCase
{
    #[Test]
    public function tinysourceReturnsSourceUnchangedWhenFrontendTypoScriptMissing(): void
    {
        $request = $this->createStub(ServerRequestInterface::class);
        $request->method('getAttribute')->with('frontend.typoscript')->willReturn(null);

        $subject = new Tinysource();
        $source = '<html><head></head><body>Hello</body></html>';

        self::assertSame($source, $subject->tinysource($source, $request));
    }

    #[Test]
    public function tinysourceSkipsWhenDisableAllHeaderCodeIsSet(): void
    {
        $frontendTypoScript = $this->createStub(FrontendTypoScript::class);
        $frontendTypoScript->method('getSetupArray')->willReturn([
            'plugin.' => [
                'ns_basetheme.' => [
                    'tinysource.' => [
                        'enable' => '1',
                    ],
                ],
            ],
        ]);
        $frontendTypoScript->method('getConfigArray')->willReturn([
            'disableAllHeaderCode' => '1',
        ]);

        $request = $this->createStub(ServerRequestInterface::class);
        $request->method('getAttribute')->with('frontend.typoscript')->willReturn($frontendTypoScript);

        $subject = new Tinysource();
        $source = "<html>\n<head>\n<title>x</title>\n</head>\n<body>\n  Hello  \n</body>\n</html>";

        self::assertSame($source, $subject->tinysource($source, $request));
    }

    #[Test]
    public function tinysourceMinifiesBodyWhenEnabled(): void
    {
        $frontendTypoScript = $this->createStub(FrontendTypoScript::class);
        $frontendTypoScript->method('getSetupArray')->willReturn([
            'plugin.' => [
                'ns_basetheme.' => [
                    'tinysource.' => [
                        'enable' => '1',
                        'body.' => [
                            'stripComments' => '0',
                        ],
                        'head.' => [
                            'stripComments' => '0',
                        ],
                    ],
                ],
            ],
        ]);
        $frontendTypoScript->method('getConfigArray')->willReturn([
            'disableAllHeaderCode' => '0',
        ]);

        $request = $this->createStub(ServerRequestInterface::class);
        $request->method('getAttribute')->with('frontend.typoscript')->willReturn($frontendTypoScript);

        $subject = new Tinysource();
        $source = "<html><head>\n<title>x</title>\n</head><body>\n  Hello  \n</body></html>";
        $result = $subject->tinysource($source, $request);

        self::assertStringContainsString('<body> Hello </body>', $result);
        self::assertStringNotContainsString("\n  Hello  \n", $result);
    }
}
