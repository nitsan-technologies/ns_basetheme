<?php
defined('TYPO3') or die();

// Add Custom TYPO3 Backend Login Screen
if (empty($GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['backend']['loginBackgroundImage'])) {
    $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['backend']['loginBackgroundImage'] = 'EXT:ns_basetheme/Resources/Public/Images/BackendLogin/Aadi-T3Planet-TYPO3-Login.jpg';
}
