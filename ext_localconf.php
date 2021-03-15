<?php

// TYPO3 Security Check
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}
$_EXTKEY = 'ns_basetheme';
if (TYPO3_MODE === 'BE' && version_compare(TYPO3_branch, '9.0', '>') && version_compare(TYPO3_branch, '10.1', '<')) {
    $class = 'TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher';
    $dispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance($class);
    $dispatcher->connect(
        'TYPO3\\CMS\\Extensionmanager\\Service\\ExtensionManagementService',
        'hasInstalledExtensions',
        'NITSAN\\NsBasetheme\\Setup',
        'executeOnSignal'
    );
}

// Let's configuration of this extension from "Extension Manager"
// $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY] = unserialize($_EXTCONF);

// Define your each componenet's flexform files
$allComponents = [];
if (version_compare(TYPO3_branch, '9.0', '>')) {
    $siteRoot = TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/typo3conf/';
    $arrAllComponents['ns_basetheme'] = scandir(\TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/typo3conf/ext/ns_basetheme/Configuration/FlexForms');
} else {
    $siteRoot = PATH_typo3conf;
    $arrAllComponents['ns_basetheme'] = scandir(PATH_typo3conf . 'ext/ns_basetheme/Configuration/FlexForms');
}
$arrAllExtensions = [];
$activePackages = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Package\PackageManager::class)->getActivePackages();
foreach ($activePackages as $package) {
    $extensionPrefixKey = substr($package->getPackageKey(), 0, 9);
    if ($extensionPrefixKey == 'ns_theme_') {
        $arrAllExtensions[] = $package->getPackageKey();
    }
}

if ($_COOKIE['NsLicense'] != '') {
    $disableExtensions = explode(',', $_COOKIE['NsLicense']);
    foreach ($disableExtensions as $ext) {
        $key = array_search($ext, $arrAllExtensions);
        unset($arrAllExtensions[$key]);
    }
}
if (count($arrAllExtensions) > 0) {
    foreach ($arrAllExtensions as $key => $extKey) {
        // Get only extension which are child theme eg., EXT:ns_theme_cleanblog
        $extensionPrefixKey = substr($extKey, 0, 9);
        if ($extensionPrefixKey == 'ns_theme_') {
            if (version_compare(TYPO3_branch, '9.0', '>')) {
                $arrAllComponents[$extKey] = scandir(\TYPO3\CMS\Core\Core\Environment::getPublicPath() . "/typo3conf/ext/$extKey/Configuration/FlexForms");
            } else {
                $arrAllComponents[$extKey] = scandir(PATH_typo3conf . "ext/$extKey/Configuration/FlexForms");
            }
        }
    }
    if (array_key_exists('ns_theme_extend', $arrAllComponents)) {
        $themeExtend = $arrAllComponents['ns_theme_extend'];
        unset($arrAllComponents['ns_theme_extend']);
        $arrAllComponents['ns_theme_extend'] = $themeExtend;
    }
    // Preparing final array with ALL components from ALL themes
    foreach ($arrAllComponents as $extKey => $extValue) {
        foreach ($extValue as $key => $value) {
            if ($value != '.' && $value != '..' && strpos($value, '.xml') !== false) {
                $theComponentName = str_replace('.xml', '', $value);
                if (!empty($theComponentName)) {
                    $allComponents[$extKey][] = $theComponentName;
                }
            }
        }
    }

    // Let's add default PageTSConfig for Backend layout, TCE form, Components etc.,
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $_EXTKEY . '/Configuration/PageTSconfig/setup.typoscript">');

    // Settled constatant to access from "Everywhere"
    define('ALL_COMPONENTS', $allComponents);

    // Include new content elements to modWizards
    if (TYPO3_MODE === 'BE') {
        call_user_func(
            function ($_EXTKEY) {
                // Get Components from ext_localconf.php
                $allComponents = constant('ALL_COMPONENTS');

                // Let's prepare CType components to add at PageTS Config
                $collectComponent = $listComponent = $tsComponents = '';
                foreach ($allComponents as $extKey => $extValue) {
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

                // Adding final CType and extra tab call "Custom Components"
                \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig("
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
                ");
            },
            $_EXTKEY
        );
        // Let's add default PageTS for "Form"
        $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['default'] = 'EXT:ns_basetheme/Configuration/RTE/Default.yaml';
    }

    // Let's prepare CType components to add at TypoScript Config
    $tsComponents = '';
    foreach ($allComponents as $extKey => $extValue) {
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

    // Add TypoScript for tt_content as setup.typoscript
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($_EXTKEY, 'setup', "
        tt_content {
            $tsComponents
        }
    ");

    // Draw content into content elements
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem'][] = 'NITSAN\\NsBasetheme\\Hooks\\CmsLayout';

    // Manipulate data if needed
    // $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:' . $_EXTKEY . '/Classes/Hooks/PreProcessFields.php:NITSAN\NsBasetheme\Hooks\PreProcessFields';

    // Let's register icon for each TYPO3 Components
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        \TYPO3\CMS\Core\Imaging\IconRegistry::class
    );
    foreach ($allComponents as $extKey => $extValue) {
        foreach ($extValue as $key => $theComponent) {
            $iconRegistry->registerIcon(
                $theComponent,
                \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
                ['source' => (file_exists($siteRoot . 'ext/' . $extKey . '/Resources/Public/Icons/' . $theComponent . '.png')) ? 'EXT:' . $extKey . '/Resources/Public/Icons/' . $theComponent . '.png' : 'EXT:ns_basetheme/Resources/Public/Icons/default_icon.png']
            );
        }
    }

    //Module Icon
    $iconRegistry->registerIcon(
        'module-nsbasetheme',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:ns_basetheme/Resources/Public/Icons/module-nitsan.svg']
    );
}