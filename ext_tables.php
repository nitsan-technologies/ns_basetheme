<?php
// TYPO3 Security Check
defined('TYPO3_MODE') or die();

// Add default include static TypoScript (for root page)
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Configuration/TypoScript',
    'Default TYPO3 Theme & Templates'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_nsthemens2019_domain_model_testimonial', 'EXT:ns_basetheme/Resources/Private/Language/locallang_csh_tx_nsthemens2019_domain_model_testimonial.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_nsthemens2019_domain_model_testimonial');
