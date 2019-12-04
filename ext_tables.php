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
