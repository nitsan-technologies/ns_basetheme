<?php
declare(strict_types=1);

namespace NITSAN\NsBasetheme;

use TYPO3\CMS\Core\Core\Environment;

/**
 * NsBasethemeUtility
 */
class NsBasethemeUtility
{
    /**
     * getChildThemeComponents
     *
     * @return array
     **/

    /**
     * getInstalledChildTheme
     *
     **/
    public function getInstalledChildTheme()
    {
        $arrAllExtensions = [];
        // @extensionScannerIgnoreLine
        $activePackages = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Package\PackageManager::class)->getActivePackages();
        if (count($activePackages) > 0) {
            foreach ($activePackages as $package) {
                $extensionPrefixKey = substr($package->getPackageKey(), 0, 9);
                if ($extensionPrefixKey == 'ns_theme_') {
                    $arrAllExtensions[] = $package->getPackageKey();
                }
            }
        }

        return $arrAllExtensions;
    }

    /**
     * setupBackendPreviewCssJs
     *
     **/
    public function setupBackendPreviewCssJs($arrAllExtensions, $siteRoot)
    {
        // Let's check if our child themes are available
        if (count($arrAllExtensions) > 0) {
            $basethemeKey = 'ns_basetheme';
            foreach ($arrAllExtensions as $key => $extKey) {
                $rExtkey = $extKey;
                if (Environment::isComposerMode()) {
                    $basethemeKey = str_replace('_', '-', $basethemeKey);
                    $extKey = str_replace('_', '-', $extKey);
                }
                // Get only extension which are child theme eg., EXT:ns_theme_cleanblog
                $extensionPrefixKey = substr($rExtkey, 0, 9);
                if ($extensionPrefixKey === 'ns_theme_') {
                    // Render Custom CSS and Javascript
                    $renderer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Page\AssetCollector::class);
                    // Grab CSS/JS of EXT.ns_basetheme
                    $css = $siteRoot . $extKey . '/Resources/Public/css/Backend.css';
                    if (file_exists($css)) {
                        // @extensionScannerIgnoreLine
                        $renderer->addStyleSheet('Base', 'EXT:ns_basetheme/Resources/Public/css/Backend.css');
                    }

                    // If available in basethem...
                    $jsNaBaseThemeImagePreview = $siteRoot .$basethemeKey. '/Resources/Public/JavaScript/ImagePreview.js';
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

                    // Grab CSS/JS of EXT.ns_theme_name
                    $css = $siteRoot . $extKey . '/Resources/Public/Backend/Css/Backend.css';
                    if (file_exists($css)) {
                        // @extensionScannerIgnoreLine
                        $renderer->addStyleSheet('Childbase', 'EXT:' . $rExtkey . '/Resources/Public/Backend/Css/Backend.css');
                    }

                    unset($css);
                    unset($js);
                }
            }
        }
    }

    /**
     * prepareWizardPageTSConfig
     *
     **/
    public function prepareWizardPageTSConfig($allComponents)
    {
        $collectComponent = $listComponent = $tsComponents = '';
        
        // Get list of Content Block identifiers to skip them
        // Content Blocks handle their own wizard registration
        $contentBlockIdentifiers = [];
        if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('content_blocks')) {
            foreach ($allComponents as $extKey => $extValue) {
                foreach ($extValue as $key => $theComponent) {
                    // Check if Content Block config.yaml exists
                    $contentBlockConfigPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName(
                        'EXT:' . $extKey . '/ContentBlocks/ContentElements/' . $theComponent . '/config.yaml'
                    );
                    if ($contentBlockConfigPath && file_exists($contentBlockConfigPath)) {
                        $configContent = @file_get_contents($contentBlockConfigPath);
                        if ($configContent && preg_match('/identifier:\s*([a-zA-Z0-9_]+)/', $configContent, $matches)) {
                            $contentBlockIdentifiers[] = $matches[1];
                        } else {
                            $contentBlockIdentifiers[] = $theComponent;
                        }
                    }
                }
            }
        }
        
        if (count($allComponents) > 0) {
            foreach ($allComponents as $extKey => $extValue) {
                if (count($extValue) > 0) {
                    foreach ($extValue as $key => $theComponent) {
                        // Skip Content Blocks - they handle their own wizard registration
                        if (in_array($theComponent, $contentBlockIdentifiers)) {
                            continue;
                        }
                        
                        $collectComponent .= "
                        
                        $theComponent {
                            iconIdentifier = ".str_replace('_','-',$theComponent)."
                            title = LLL:EXT:$extKey/Resources/Private/Language/locallang_db.xlf:wizard.$theComponent
                            description = LLL:EXT:$extKey/Resources/Private/Language/locallang_db.xlf:wizard.$theComponent.desc
                            tt_content_defValues {
                                CType = $theComponent
                            }
                        }
                        ";
                        $listComponent .= $theComponent . ',';
                        $tsComponents .= '
                        ' . $theComponent . ' < .ns_default
                        ' . $theComponent . '.templateName = ' . ucfirst($theComponent) . '
                        ';
                    }
                }
            }
        }

        $pageTSConfig = "
            # Add new custom wizard for our Components
            mod.wizards.newContentElement.wizardItems.extra {

            # Set caption
            header = Custom Elements
            icon =

            # Register each Elements
            elements {
                    $collectComponent
            }
            show := addToList($listComponent)
            }
        ";
        return $pageTSConfig;
    }

    /**
     * setupComponentWiseTypoScript
     *
     **/
    public function setupComponentWiseTypoScript($allComponents)
    {
        $tsComponents = '';
        
        // Get list of Content Block identifiers to skip them
        // Content Blocks handle their own TypoScript rendering
        $contentBlockIdentifiers = [];
        if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('content_blocks')) {
            foreach ($allComponents as $extKey => $extValue) {
                foreach ($extValue as $key => $theComponent) {
                    // Check if Content Block config.yaml exists
                    $contentBlockConfigPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName(
                        'EXT:' . $extKey . '/ContentBlocks/ContentElements/' . $theComponent . '/config.yaml'
                    );
                    if ($contentBlockConfigPath && file_exists($contentBlockConfigPath)) {
                        $configContent = @file_get_contents($contentBlockConfigPath);
                        if ($configContent && preg_match('/identifier:\s*([a-zA-Z0-9_]+)/', $configContent, $matches)) {
                            $contentBlockIdentifiers[] = $matches[1];
                        } else {
                            $contentBlockIdentifiers[] = $theComponent;
                        }
                    }
                }
            }
        }
        
       
        return $tsComponents;
    }

    /**
     * getDirectoryPath
     *
     **/
    public function getDirectoryPath($extensionKey, $pathName)
    {
        $returnPath = '';
        if (\TYPO3\CMS\Core\Core\Environment::isComposerMode()) {
            $extensionKey = str_replace('_', '-', $extensionKey);
            $returnPath = \TYPO3\CMS\Core\Core\Environment::getProjectPath() . "/vendor/nitsan/$extensionKey/$pathName";
        } else {
            $returnPath = \TYPO3\CMS\Core\Core\Environment::getPublicPath() . "/typo3conf/ext/$extensionKey/$pathName";
        }
        return $returnPath;
    }
}
