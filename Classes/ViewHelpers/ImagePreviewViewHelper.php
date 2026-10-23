<?php

declare(strict_types=1);

namespace NITSAN\NsBasetheme\ViewHelpers;

use NITSAN\NsBasetheme\NsBasethemeUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class ImagePreviewViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('selectedValue', 'string', 'Currently selected value', false, '');
        $this->registerArgument('id', 'string', 'ID of the select element', true);
        $this->registerArgument('baseThemeRootPath', 'string', 'Base path for the theme images', false, '');
        $this->registerArgument('currentThemeName', 'string', 'Current theme name', false, '');
        $this->registerArgument('siteRootPath', 'string', 'Root path of the site', true);
        $this->registerArgument('value', 'string', 'The value of the option', true);
        $this->registerArgument('label', 'string', 'The label of the option', true);
        $this->registerArgument('params', 'array', 'Parameters for options', false, []);
        $this->registerArgument('name', 'string', 'Parameters for options', false, '');
    }

    public function render(): string
    {
        $path = $this->buildPreviewImagePath(
            (string)($this->arguments['id'] ?? ''),
            (string)($this->arguments['value'] ?? ''),
            (string)($this->arguments['currentThemeName'] ?? '')
        );

        return GeneralUtility::locationHeaderUrl(PathUtility::getPublicResourceWebPath($path));
    }

    public function buildPreviewImagePath(string $id, string $value, string $currentThemeName): string
    {
        if ($currentThemeName === '') {
            $themes = GeneralUtility::makeInstance(NsBasethemeUtility::class)->getInstalledChildTheme();
            $currentThemeName = $themes[0] ?? 'ns_basetheme';
        }

        $parts = explode('-', $id);
        $selectBoxName = (string)end($parts);
        $imageExtension = $selectBoxName === 'loader' ? '.gif' : '.png';

        return 'EXT:' . $currentThemeName . '/Resources/Public/Backend/ThemeOptionsPreview/'
            . $selectBoxName . '/' . htmlspecialchars($value) . $imageExtension;
    }
}
