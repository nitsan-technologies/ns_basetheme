<?php

declare(strict_types=1);

defined('TYPO3') or die();

$_EXTKEY = 'ns_basetheme';
// Add default include static TypoScript (for root page)
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Configuration/TypoScript',
    'Parent theme'
);
