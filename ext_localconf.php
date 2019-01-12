<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

// get configuration
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY] = unserialize($_EXTCONF);

// Include new content elements to modWizards
if (TYPO3_MODE === 'BE') {
    call_user_func(
        function ($_EXTKEY) {
            // Get the extension configuration
            $extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);
            
                \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:'.$_EXTKEY.'/Configuration/PageTSconfig/BackendLayouts.ts">');

                \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:'.$_EXTKEY.'/Configuration/PageTSconfig/TceForm.ts">');
                \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:site_default/Configuration/PageTSconfig/FluidStyledContent.ts">');
        },
        $_EXTKEY
    );
    $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['default'] = 'EXT:site_default/Configuration/PageTSconfig/TceForm/Default.yaml';
}


// draw content into content elements
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem'][] =
	'NITSAN\\site_default\\Hooks\\CmsLayout';

// manipulate data if needed
/*
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] =
'EXT:' . $_EXTKEY . '/Classes/Hooks/PreProcessFields.php:NITSAN\site_default\Hooks\PreProcessFields';
 */

/* set iconidentifier */
$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
	\TYPO3\CMS\Core\Imaging\IconRegistry::class
);
$typeArray = [
	'ns_text',
	'ns_image',
	'ns_serviceteaser',
	'ns_imageteaser',
	
];
foreach ($typeArray as $currentType) {
	$iconRegistry->registerIcon(
		$currentType,
		\TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
		['source' => 'EXT:site_default/Resources/Public/Icons/' . $currentType . '.png']
	);
}
