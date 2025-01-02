<?php

namespace NITSAN\NsBasetheme\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

// @extensionScannerIgnoreFile
class ThemeContainsViewHelper extends AbstractViewHelper
{
    public function initializeArguments()
    {
        $this->registerArgument('value', 'string', '', true);
    }

    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        switch ($arguments['value']) {
            case 'ns_basetheme':
            case 'ns_seo':
            case 'ns_gdpr':
            case 'ns_style':
            case 'ns_integration':
                return $arguments['value'];
        }
    }
}
