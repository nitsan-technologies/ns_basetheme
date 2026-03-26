<?php
declare(strict_types=1);

namespace NITSAN\NsBasetheme\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

// @extensionScannerIgnoreFile
class ThemeContainsViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('value', 'string', '', true);
    }

    public function render(): string
    {
        $result = static::renderStatic(
            $this->arguments,
            $this->renderChildrenClosure,
            $this->renderingContext
        );
        return (string)($result ?? '');
    }

    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): ?string {
        switch ($arguments['value']) {
            case 'ns_basetheme':
            case 'ns_seo':
            case 'ns_gdpr':
            case 'ns_style':
            case 'ns_integration':
            case 'ns_theme_t3karma':
                return $arguments['value'];
        }
    }
}
