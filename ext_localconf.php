<?php
// TYPO3 Security Check
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

// Let's configuration of this extension from "Extension Manager"
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY] = unserialize($_EXTCONF);

// Define your each componenet's flexform files
$allComponents = array();
$arrAllComponents = scandir("typo3conf/ext/site_default/Configuration/FlexForms");
foreach ($arrAllComponents as $key=>$value) {
  if($value != '.' && $value != '..') {
    $allComponents[] = str_replace(".xml","",$value);
  }
}

define("ALL_COMPONENTS", $allComponents);

// Include new content elements to modWizards
if (TYPO3_MODE === 'BE') {
    call_user_func(
        function ($_EXTKEY) {
            // Get the extension configuration
            $extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);
            
            // Let's add default PageTSConfig for Backend layout, TCE form, Components etc.,
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:'.$_EXTKEY.'/Configuration/PageTSconfig/setup.ts">');

            // Get Components from ext_localconf.php
            $allComponents = constant('ALL_COMPONENTS');

            // Let's prepare CType components to add at PageTS Config
            $collectComponent = $listComponent = $tsComponents = '';
            foreach ($allComponents as $theComponent) {
                $collectComponent .= "
                    $theComponent {
                      iconIdentifier = $theComponent
                      title = LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:wizard.$theComponent
                      description = LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:wizard.$theComponent.desc
                      tt_content_defValues {
                          CType = $theComponent
                      }
                    }
                ";
                $listComponent .= $theComponent.',';
                $tsComponents .= '
                    '.$theComponent.' < .ns_default
                    '.$theComponent.'.templateName = '.ucfirst($theComponent).'
                ';
            }
            
            // Adding final CType and extra tab call "Custom Components"
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig("
                # Add new custom wizard for our Components
                mod.wizards.newContentElement.wizardItems.extra {
                   
                   # Set caption
                   header = Custom Components
                   icon = 

                   # Register each Components
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
    $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['default'] = 'EXT:site_default/Configuration/RTE/Default.yaml';
}

// Draw content into content elements
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem'][] = 'NITSAN\\site_default\\Hooks\\CmsLayout';

// Manipulate data if needed
// $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:' . $_EXTKEY . '/Classes/Hooks/PreProcessFields.php:NITSAN\site_default\Hooks\PreProcessFields';

// Let's register icon for each TYPO3 Components
$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
  \TYPO3\CMS\Core\Imaging\IconRegistry::class
);
foreach ($allComponents as $theComponent) {
	$iconRegistry->registerIcon(
		$theComponent,
		\TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
		['source' => 'EXT:site_default/Resources/Public/Icons/' . $theComponent . '.png']
	);
}