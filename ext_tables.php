<?php
// TYPO3 Security Check
defined('TYPO3_MODE') or die();

// Add default include static TypoScript (for root page)
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Configuration/TypoScript',
    'Default TYPO3 Theme & Templates'
);
