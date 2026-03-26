<?php
defined('TYPO3') or die();

use NITSAN\NsBasetheme\Hooks\BackendUserLogin;
use TYPO3\CMS\Core\Core\Environment;

// Setup for before and after extension Installation
$_EXTKEY = 'ns_basetheme';


// Get sites' rootPath
$siteRoot = \TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/typo3conf/ext/';
if (Environment::isComposerMode()) {
    $siteRoot = Environment::getProjectPath() . '/vendor/nitsan/';
}
// Icon registration moved to Configuration/Icons.php for TYPO3 14 compatibility





$extensionConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)
->get('ns_basetheme');

// Add custom TYPO3 backend login screen.
if (empty($GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['backend']['loginBackgroundImage'])) {
    $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['backend']['loginBackgroundImage'] = 'EXT:ns_basetheme/Resources/Public/Images/BackendLogin/TYPO3-Rise-Background-2022.png';
}

