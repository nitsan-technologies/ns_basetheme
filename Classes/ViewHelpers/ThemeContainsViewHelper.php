<?php

declare(strict_types=1);

namespace NITSAN\NsBasetheme\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class ThemeContainsViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('value', 'string', 'Extension or theme key', true);
    }

    public function render(): string
    {
        $value = (string)($this->arguments['value'] ?? '');

        return match ($value) {
            'ns_basetheme',
            'ns_seo',
            'ns_gdpr',
            'ns_style',
            'ns_integration',
            'ns_theme_t3karma' => $value,
            default => '',
        };
    }
}
