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
// Let's register icon for each TYPO3 Components
$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\Imaging\IconRegistry::class
);

if (!\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('mask')) {
    // Initiate NsBaseThemeUtility
    $objNsBasetheme = TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\NITSAN\NsBasetheme\NsBasethemeUtility::class);

    // Initiate with blank list of themes + components
    $allComponents = $arrAllExtensions = [];

    // Get list of all the themes which starts from EXT.ns_theme_*
    $arrAllExtensions = $objNsBasetheme->getInstalledChildTheme();

    // Get list of all the components from EXT.ns_theme_*
    $allComponents = $objNsBasetheme->getChildThemeComponents($arrAllExtensions);

    // Settled constatant to access from "Everywhere"
    if (!defined('ALL_COMPONENTS')) define('ALL_COMPONENTS', $allComponents);
    if (!defined('ALL_CHILD_THEMES')) define('ALL_CHILD_THEMES', $allComponents);

    // Let's prepare CType components to add at PageTS Config
    $pageTSConfig = $objNsBasetheme->prepareWizardPageTSConfig($allComponents);

    // Adding final CType and extra tab call "Custom Components"
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig($pageTSConfig);

    // Let's prepare CType components to add at TypoScript Config
    $tsComponents = $objNsBasetheme->setupComponentWiseTypoScript($allComponents);

    // Add TypoScript for tt_content as setup.typoscript
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($_EXTKEY, 'setup', "
        tt_content {
            $tsComponents
        }
    ");
}

// Let's add default PageTS for "Form"
$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['default'] = 'EXT:ns_basetheme/Configuration/RTE/Default.yaml';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
    @import "EXT:' . $_EXTKEY . '/Configuration/PageTSconfig/setup.tsconfig"
');

$iconIdentifiers = [
    'submodule-nsbasetheme',
    'module-nsbasetheme',
];
// Let's register module's icon
foreach ($iconIdentifiers as $identifier) {
    $iconRegistry->registerIcon(
        $identifier,
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:ns_basetheme/Resources/Public/Icons/'.$identifier.'.svg']
    );
}