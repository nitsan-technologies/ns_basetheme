<?php

namespace NITSAN\NsBasetheme;

/**
 * NsBasethemeUtility
 */
class NsBasethemeUtility {

    /**
     * getChildThemeComponents
     *
     * @return void
     **/
    public function getChildThemeComponents($arrAllExtensions) {
        $allComponents = [];

        // Get default components from EXT:ns_basetheme
        if (version_compare(TYPO3_branch, '9.0', '>')) {
            $arrAllComponents['ns_basetheme'] = scandir(\TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/typo3conf/ext/ns_basetheme/Configuration/FlexForms');
        } else {
            $arrAllComponents['ns_basetheme'] = scandir(PATH_typo3conf . 'ext/ns_basetheme/Configuration/FlexForms');
        }

        // Get only extension which are child theme eg., EXT:ns_theme_cleanblog
        if(count($arrAllExtensions) > 0) {
            foreach ($arrAllExtensions as $key => $extKey) {

                $extensionPrefixKey = substr($extKey, 0, 9);
                if ($extensionPrefixKey == 'ns_theme_') {
                    if (version_compare(TYPO3_branch, '9.0', '>')) {
                        $directoryPath = \TYPO3\CMS\Core\Core\Environment::getPublicPath() . "/typo3conf/ext/$extKey/Configuration/FlexForms";
                        if(is_dir($directoryPath)) {
                            $arrAllComponents[$extKey] = scandir($directoryPath);
                        }
                    } else {
                        $directoryPath = PATH_typo3conf . "ext/$extKey/Configuration/FlexForms";
                        if(is_dir($directoryPath)) {
                            $arrAllComponents[$extKey] = scandir($directoryPath);
                        }
                    }
                }
            }
        }

        // Let's ignore EXT:ns_theme_extend
        if (array_key_exists('ns_theme_extend', $arrAllComponents)) {
            $themeExtend = $arrAllComponents['ns_theme_extend'];
            unset($arrAllComponents['ns_theme_extend']);
            $arrAllComponents['ns_theme_extend'] = $themeExtend;
        }

        // Preparing final array with ALL components from ALL themes
        if(count($arrAllComponents) > 0) {
            foreach ($arrAllComponents as $extKey => $extValue) {

                if(count($extValue) > 0) {
                    foreach ($extValue as $key => $value) {
                        if ($value != '.' && $value != '..' && strpos($value, '.xml') !== false) {
                            $theComponentName = str_replace('.xml', '', $value);
                            if (!empty($theComponentName)) {
                                $allComponents[$extKey][] = $theComponentName;
                            }
                        }
                    }
                }
            }
        }

        return $allComponents;
    }

    /**
     * getInstalledChildTheme
     *
     * @return void
     **/
    public function getInstalledChildTheme() {
        $arrAllExtensions = [];

        $activePackages = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Package\PackageManager::class)->getActivePackages();
        if(count($activePackages) > 0) {
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
     * @return void
     **/
    public function setupBackendPreviewCssJs($arrAllExtensions, $siteRoot) {

        // Let's check if our child themes are available
        if (count($arrAllExtensions) > 0) {
            foreach ($arrAllExtensions as $key => $extKey) {
                
                // Get only extension which are child theme eg., EXT:ns_theme_cleanblog
                $extensionPrefixKey = substr($extKey, 0, 9);
                if ($extensionPrefixKey == 'ns_theme_') {
                    
                    // Render Custom CSS and Javascript
                    $renderer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Page\PageRenderer::class);

                    // Grab CSS/JS of EXT.ns_basetheme
                    $css = $siteRoot . 'ext/ns_basetheme/Resources/Public/css/Backend.css';
                    if (file_exists($css)) {
                        $renderer->addCssFile('EXT:ns_basetheme/Resources/Public/css/Backend.css', 'stylesheet', 'all');
                    }
                    $jsNaBaseThemeBackend = $siteRoot . 'ext/ns_basetheme/Resources/Public/JavaScript/Backend.js';
                    $jsNaBaseThemeImagePreview = $siteRoot . 'ext/ns_basetheme/Resources/Public/JavaScript/ImagePreview.js';
                    if (file_exists($jsNaBaseThemeImagePreview)) {
                        $renderer->addJsFile('EXT:ns_basetheme/Resources/Public/JavaScript/ImagePreview.js', 'text/javascript', false);
                    }
                    if (file_exists($jsNaBaseThemeBackend)) {
                        $renderer->addJsFile('EXT:ns_basetheme/Resources/Public/JavaScript/Backend.js', 'text/javascript', false);
                    }

                    // Grab CSS/JS of EXT.ns_theme_name
                    $css = $siteRoot . 'ext/' . $extKey . '/Resources/Public/Backend/Css/Backend.css';
                    $js = $siteRoot . 'ext/' . $extKey . '/Resources/Public/Backend/JavaScript/Backend.js';
                    if (file_exists($css)) {
                        $renderer->addCssFile('EXT:' . $extKey . '/Resources/Public/Backend/Css/Backend.css', 'stylesheet', 'all');
                    }
                    if (file_exists($js)) {
                        $renderer->addJsFile('EXT:' . $extKey . '/Resources/Public/Backend/JavaScript/Backend.js', 'text/javascript', false);
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
     * @return void
     **/
    public function prepareWizardPageTSConfig($allComponents) {
        $collectComponent = $listComponent = $tsComponents = '';
        if (count($allComponents) > 0) {
            foreach ($allComponents as $extKey => $extValue) {

                if (count($extValue) > 0) {
                    foreach ($extValue as $key => $theComponent) {
                        $collectComponent .= "
                        $theComponent {
                        iconIdentifier = $theComponent
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
     * @return void
     **/
    public function setupComponentWiseTypoScript($allComponents) {
        $tsComponents = '';
        if (count($allComponents) > 0) {
            foreach ($allComponents as $extKey => $extValue) {

                if (count($extValue) > 0) {
                    foreach ($extValue as $key => $theComponent) {
                        $arrTemplateName = explode('_', $theComponent);
                        $templateName = ucfirst($arrTemplateName[0]) . '' . ucfirst($arrTemplateName[1]);
                        if (!empty($templateName)) {
                            $tsComponents .= "
                                $theComponent = FLUIDTEMPLATE
                                $theComponent {
                                    templateRootPaths {
                                        0 = EXT:fluid_styled_content/Resources/Private/Templates/
                                        10 = EXT:$extKey/Resources/Private/Components/
                                    }
                                    partialRootPaths {
                                        0 = EXT:fluid_styled_content/Resources/Private/Partials/
                                        10 = EXT:$extKey/Resources/Private/Partials/
                                    }
                                    variables {
                                    }
                                    templateName = $templateName
                                    dataProcessing {
                                        10 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
                                        10 {
                                            references.fieldName = image
                                            as = image
                                        }
                                        20 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
                                        20 {
                                            references.fieldName = media
                                            as = media
                                        }
                                        30 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
                                        30 {
                                            references.fieldName = file2
                                            as = file2
                                        }
                                        40 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
                                        40 {
                                            references.fieldName = file3
                                            as = file3
                                        }
                                        50 = NITSAN\\NsBasetheme\\DataProcessing\\DefaultProcessor
                                    }
                                }
                            ";
                        }
                    }
                }
            }
        }
        return $tsComponents;
    }
}
