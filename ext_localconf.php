<?php
// TYPO3 Security Check
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}
$_EXTKEY = 'ns_basetheme';
// Let's configuration of this extension from "Extension Manager"
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY] = unserialize($_EXTCONF);

// Define your each componenet's flexform files
$allComponents = array();
if (version_compare(TYPO3_branch, '10.0', '>')) {
    $arrAllComponents['ns_basetheme'] = scandir(\TYPO3\CMS\Core\Core\Environment::getPublicPath() . "/typo3conf/ext/ns_basetheme/Configuration/FlexForms");
} else {
    $arrAllComponents['ns_basetheme'] = scandir(PATH_typo3conf . "ext/ns_basetheme/Configuration/FlexForms");
}

// Get list of all the extensions
if (version_compare(TYPO3_branch, '10.0', '>')) {
    $arrAllExtensions = scandir(\TYPO3\CMS\Core\Core\Environment::getPublicPath() . "/typo3conf/ext/");
} else {
    $arrAllExtensions = scandir(PATH_typo3conf . "ext/");
}
foreach ($arrAllExtensions as $key => $extKey) {
    // Get only extension which are child theme eg., EXT:ns_theme_cleanblog
    $extensionPrefixKey = substr($extKey, 0, 9);
    if ($extensionPrefixKey == "ns_theme_") {
        if (version_compare(TYPO3_branch, '10.0', '>')) {
            $arrAllComponents[$extKey] = scandir(\TYPO3\CMS\Core\Core\Environment::getPublicPath() . "/typo3conf/ext/$extKey/Configuration/FlexForms");
        } else {
            $arrAllComponents[$extKey] = scandir(PATH_typo3conf . "ext/$extKey/Configuration/FlexForms");
        }
    }
}

// Preparing final array with ALL components from ALL themes
foreach ($arrAllComponents as $extKey => $extValue) {
    foreach ($extValue as $key => $value) {
        if ($value != '.' && $value != '..') {
            $theComponentName = str_replace(".xml", "", $value);
            if (!empty($theComponentName)) {
                $allComponents[$extKey][] = $theComponentName;
            }
        }
    }
}

// Settled constatant to access from "Everywhere"
define("ALL_COMPONENTS", $allComponents);

// Include new content elements to modWizards
if (TYPO3_MODE === 'BE') {
    call_user_func(
        function ($_EXTKEY) {
            // Get the extension configuration
            $extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);

            // Let's add default PageTSConfig for Backend layout, TCE form, Components etc.,
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $_EXTKEY . '/Configuration/PageTSconfig/setup.ts">');

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
        $arrTemplateName = explode("_", $theComponent);
        $templateName = ucfirst($arrTemplateName[0]) . "" . ucfirst($arrTemplateName[1]);
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
                        20 = NITSAN\\ns_basetheme\\DataProcessing\\DefaultProcessor
                    }
                }
            ";
        }
    }
}

// Add TypoScript for tt_content as setup.ts
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($_EXTKEY, 'setup', "
    tt_content {
        $tsComponents
    }
");

// Draw content into content elements
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem'][] = 'NITSAN\\ns_basetheme\\Hooks\\CmsLayout';

// Manipulate data if needed
// $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:' . $_EXTKEY . '/Classes/Hooks/PreProcessFields.php:NITSAN\ns_basetheme\Hooks\PreProcessFields';

// Let's register icon for each TYPO3 Components
$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\Imaging\IconRegistry::class
);

foreach ($allComponents as $extKey => $extValue) {
    foreach ($extValue as $key => $theComponent) {
        $iconRegistry->registerIcon(
            $theComponent,
            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
            ['source' => 'EXT:' . $extKey . '/Resources/Public/Icons/' . $theComponent . '.png']
        );
    }
}
