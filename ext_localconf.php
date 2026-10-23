<?php

declare(strict_types=1);

defined('TYPO3') or die();

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('ns_basetheme');

if (empty($GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['backend']['loginBackgroundImage'])) {
    $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['backend']['loginBackgroundImage']
        = 'EXT:ns_basetheme/Resources/Public/Images/BackendLogin/TYPO3-Rise-Background-2022.png';
}
