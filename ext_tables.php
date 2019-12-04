<?php
// TYPO3 Security Check
defined('TYPO3_MODE') or die();

$_EXTKEY = 'ns_basetheme';
// Add default include static TypoScript (for root page)
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Configuration/TypoScript',
    '[NITSAN] EXT:ns_basetheme: Default Theme'
);
if (version_compare(TYPO3_branch, '9.0', '>')) {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['Backend\Template\Components\ButtonBar']['getButtonsHook'][] = 'NITSAN\ns_basetheme\Hooks\SaveCloseHook->addSaveCloseButton';
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['Backend\Template\Components\ButtonBar']['getButtonsHook'][] = 'NITSAN\ns_basetheme\Hooks\SaveCloseHook->addSaveShowButton';
}
