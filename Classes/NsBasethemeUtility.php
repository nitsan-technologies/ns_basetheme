<?php

declare(strict_types=1);

namespace NITSAN\NsBasetheme;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class NsBasethemeUtility
{
    public function getInstalledChildTheme(): array
    {
        $arrAllExtensions = [];
        $activePackages = GeneralUtility::makeInstance(PackageManager::class)->getActivePackages();
        foreach ($activePackages as $package) {
            $packageKey = $package->getPackageKey();
            if (str_starts_with($packageKey, 'ns_theme_')) {
                $arrAllExtensions[] = $packageKey;
            }
        }

        return $arrAllExtensions;
    }

    public function setupBackendPreviewCssJs(array $arrAllExtensions, string $siteRoot): void
    {
        if ($arrAllExtensions === []) {
            return;
        }

        $basethemeKey = 'ns_basetheme';
        $renderer = GeneralUtility::makeInstance(AssetCollector::class);

        foreach ($arrAllExtensions as $extKey) {
            $rExtkey = $extKey;
            if (Environment::isComposerMode()) {
                $basethemeKey = str_replace('_', '-', $basethemeKey);
                $extKey = str_replace('_', '-', $extKey);
            }

            if (!str_starts_with($rExtkey, 'ns_theme_')) {
                continue;
            }

            $css = $siteRoot . $extKey . '/Resources/Public/css/Backend.css';
            if (file_exists($css)) {
                $renderer->addStyleSheet('Base', 'EXT:ns_basetheme/Resources/Public/css/Backend.css');
            }

            $jsNaBaseThemeImagePreview = $siteRoot . $basethemeKey . '/Resources/Public/JavaScript/ImagePreview.js';
            if (file_exists($jsNaBaseThemeImagePreview)) {
                $renderer->addJavaScript('Bastheme-ImagePreview-Js', 'EXT:ns_basetheme/Resources/Public/JavaScript/ImagePreview.js');
            }

            $js = $siteRoot . $extKey . '/Resources/Public/Backend/JavaScript/ThemeBackend.js';
            if (file_exists($js)) {
                $renderer->addJavaScript('ChildbaseJS', 'EXT:' . $rExtkey . '/Resources/Public/Backend/JavaScript/ThemeBackend.js');
            }

            $jsNaBaseThemeBackend = $siteRoot . $basethemeKey . '/Resources/Public/JavaScript/Backend.js';
            if (file_exists($jsNaBaseThemeBackend)) {
                $renderer->addJavaScript('Bastheme-Backend-Js', 'EXT:ns_basetheme/Resources/Public/JavaScript/Backend.js');
            }

            $childCss = $siteRoot . $extKey . '/Resources/Public/Backend/Css/Backend.css';
            if (file_exists($childCss)) {
                $renderer->addStyleSheet('Childbase', 'EXT:' . $rExtkey . '/Resources/Public/Backend/Css/Backend.css');
            }
        }
    }

    public function prepareWizardPageTSConfig(array $allComponents): string
    {
        $collectComponent = '';
        $listComponent = '';
        $contentBlockIdentifiers = $this->getContentBlockIdentifiers($allComponents);

        foreach ($allComponents as $extKey => $extValue) {
            if (!is_array($extValue) || $extValue === []) {
                continue;
            }
            foreach ($extValue as $theComponent) {
                if (in_array($theComponent, $contentBlockIdentifiers, true)) {
                    continue;
                }

                $iconIdentifier = str_replace('_', '-', (string)$theComponent);
                $collectComponent .= '
                        ' . $theComponent . ' {
                            iconIdentifier = ' . $iconIdentifier . '
                            title = LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xlf:wizard.' . $theComponent . '
                            description = LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xlf:wizard.' . $theComponent . '.desc
                            tt_content_defValues {
                                CType = ' . $theComponent . '
                            }
                        }
                        ';
                $listComponent .= $theComponent . ',';
            }
        }

        return '
            # Add new custom wizard for our Components
            mod.wizards.newContentElement.wizardItems.extra {
            header = Custom Elements
            icon =
            elements {
                    ' . $collectComponent . '
            }
            show := addToList(' . $listComponent . ')
            }
        ';
    }

    public function setupComponentWiseTypoScript(array $allComponents): string
    {
        unset($allComponents);
        return '';
    }

    public function getDirectoryPath(string $extensionKey, string $pathName): string
    {
        if (Environment::isComposerMode()) {
            $extensionKey = str_replace('_', '-', $extensionKey);
            return Environment::getProjectPath() . '/vendor/nitsan/' . $extensionKey . '/' . $pathName;
        }

        return Environment::getPublicPath() . '/typo3conf/ext/' . $extensionKey . '/' . $pathName;
    }

    /**
     * @param array<string, array<int, string>> $allComponents
     * @return list<string>
     */
    protected function getContentBlockIdentifiers(array $allComponents): array
    {
        $contentBlockIdentifiers = [];
        try {
            $contentBlocksLoaded = ExtensionManagementUtility::isLoaded('content_blocks');
        } catch (\Throwable) {
            $contentBlocksLoaded = false;
        }
        if (!$contentBlocksLoaded) {
            return $contentBlockIdentifiers;
        }

        foreach ($allComponents as $extKey => $extValue) {
            foreach ($extValue as $theComponent) {
                $contentBlockConfigPath = GeneralUtility::getFileAbsFileName(
                    'EXT:' . $extKey . '/ContentBlocks/ContentElements/' . $theComponent . '/config.yaml'
                );
                if ($contentBlockConfigPath && file_exists($contentBlockConfigPath)) {
                    $configContent = (string)file_get_contents($contentBlockConfigPath);
                    if (preg_match('/identifier:\s*([a-zA-Z0-9_]+)/', $configContent, $matches)) {
                        $contentBlockIdentifiers[] = $matches[1];
                    } else {
                        $contentBlockIdentifiers[] = $theComponent;
                    }
                }
            }
        }

        return $contentBlockIdentifiers;
    }
}
