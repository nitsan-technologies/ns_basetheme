<?php
// TYPO3 Security Check
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

// Let's configuration of this extension from "Extension Manager"
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY] = unserialize($_EXTCONF);

// Include new content elements to modWizards
if (TYPO3_MODE === 'BE') {
    call_user_func(
        function ($_EXTKEY) {
            // Get the extension configuration
            $extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);
            
            // Let's add default PageTSConfig for Backend layout, TCE form, Components etc.,
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:'.$_EXTKEY.'/Configuration/PageTSconfig/setup.ts">');
        },
        $_EXTKEY
    );
    // Let's add default PageTS for "Form"
    $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['default'] = 'EXT:site_default/Configuration/PageTSconfig/TceForm/Default.yaml';
}

// Draw content into content elements
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem'][] = 'NITSAN\\site_default\\Hooks\\CmsLayout';

// Manipulate data if needed
// $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:' . $_EXTKEY . '/Classes/Hooks/PreProcessFields.php:NITSAN\site_default\Hooks\PreProcessFields';

// Define your list of all the Components
$allComponents = [
	'ns_imageteaser',	
];

define("ALL_COMPONENTS", $allComponents);

// Let's register icon for each TYPO3 Components
$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
  \TYPO3\CMS\Core\Imaging\IconRegistry::class
);
foreach ($allComponents as $theComponent) {
	$iconRegistry->registerIcon(
		$theComponent,
		\TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
		['source' => 'EXT:site_default/Resources/Public/components-icons/' . $theComponent . '.png']
	);
}