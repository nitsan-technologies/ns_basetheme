<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Site Default');


$GLOBALS['TCA']['tt_content']['types']['CType']['subtypes_addlist']['ns_serviceteaser'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
   '',
   'FILE:EXT:site_default/Configuration/FlexForms/ns_serviceteaser_flexform.xml',
   'ns_serviceteaser'
);

$GLOBALS['TCA']['tt_content']['types']['CType']['subtypes_addlist']['ns_imageteaser'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
   '',
   'FILE:EXT:site_default/Configuration/FlexForms/ns_imageteaser_flexform.xml',
   'ns_imageteaser'
);
