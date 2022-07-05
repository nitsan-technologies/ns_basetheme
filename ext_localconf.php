<?php

// TYPO3 Security Check
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// Setup for before and after extension Installation
$_EXTKEY = 'ns_basetheme';
if (TYPO3_MODE === 'BE' && version_compare(TYPO3_branch, '8.0', '=')) {
    // Before Activate Extension
    $class = 'TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher';
    $dispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance($class);
    $dispatcher->connect(
        'TYPO3\\CMS\\Extensionmanager\\Service\\ExtensionManagementService',
        'hasInstalledExtensions',
        'NITSAN\\NsBasetheme\\Setup',
        'executeOnSignalAfter'
    );
} elseif (TYPO3_MODE === 'BE' && version_compare(TYPO3_branch, '9.0', '=')) {
    // After Activate Extension
    $signalSlotDispatcher = TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
    $signalSlotDispatcher->connect(
        \TYPO3\CMS\Extensionmanager\Utility\InstallUtility::class,
        'afterExtensionInstall',
        \NITSAN\NsBasetheme\Setup::class,
        'executeOnSignalAfter'
    );
}

// Let's get configuration of this extension from "Extension Manager"
// $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY] = unserialize($_EXTCONF);

// Get sites' rootPath
$siteRoot = (version_compare(TYPO3_branch, '9.0', '>')) ? \TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/typo3conf/' : PATH_typo3conf;

// Initiate NsBaseThemeUtility
$objNsBasetheme = TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\NITSAN\NsBasetheme\NsBasethemeUtility::class);

// Initiate with blank list of themes + components
$allComponents = $arrAllExtensions = [];

// Get list of all the themes which starts from EXT.ns_theme_*
$arrAllExtensions = $objNsBasetheme->getInstalledChildTheme();

// Get list of all the components from EXT.ns_theme_*
$allComponents = $objNsBasetheme->getChildThemeComponents($arrAllExtensions);

// Settled constatant to access from "Everywhere"
define('ALL_COMPONENTS', $allComponents);

// Are you in Backend?
if (TYPO3_MODE === 'BE') {

    // Let's make beautiful backend preview by adding custom CSS/JS
    $objNsBasetheme->setupBackendPreviewCssJs($arrAllExtensions, $siteRoot);

    // Let's prepare CType components to add at PageTS Config
    $pageTSConfig = $objNsBasetheme->prepareWizardPageTSConfig($allComponents);

    // Adding final CType and extra tab call "Custom Components"
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig($pageTSConfig);

    // Let's add default PageTS for "Form"
    $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['default'] = 'EXT:ns_basetheme/Configuration/RTE/Default.yaml';
}

// Let's add default PageTSConfig for Backend layout, TCE form, Components etc.,
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $_EXTKEY . '/Configuration/PageTSconfig/setup.tsconfig">');

// Let's prepare CType components to add at TypoScript Config
$tsComponents = $objNsBasetheme->setupComponentWiseTypoScript($allComponents);

// Add TypoScript for tt_content as setup.typoscript
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($_EXTKEY, 'setup', "
    tt_content {
        $tsComponents
    }
");

// Draw content into content elements
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem'][] = 'NITSAN\\NsBasetheme\\Hooks\\CmsLayout';

// Manipulate data if needed
// $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:' . $_EXTKEY . '/Classes/Hooks/PreProcessFields.php:NITSAN\NsBasetheme\Hooks\PreProcessFields';

// Let's register icon for each TYPO3 Components
$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\Imaging\IconRegistry::class
);
if (count($allComponents) > 0) {
    foreach ($allComponents as $extKey => $extValue) {
        
        if (count($extValue) > 0) {
            foreach ($extValue as $key => $theComponent) {
                $iconRegistry->registerIcon(
                    $theComponent,
                    \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
                    ['source' => (file_exists($siteRoot . 'ext/' . $extKey . '/Resources/Public/Icons/' . $theComponent . '.png')) ? 'EXT:' . $extKey . '/Resources/Public/Icons/' . $theComponent . '.png' : 'EXT:ns_basetheme/Resources/Public/Icons/default_icon.png']
                );
            }
        }
    }
}

// Let's register module's icon
$iconRegistry->registerIcon(
    'module-nsbasetheme',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:ns_basetheme/Resources/Public/Icons/module-nitsan.svg']
);

// Register hook on successful BE user login
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_userauthgroup.php']['backendUserLogin'][] =
    \NITSAN\NsBasetheme\Hooks\BackendUserLogin::class . '->dispatch';

// Register tiny source
if (!isset($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'] = [];
}
array_unshift(
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'],
    'NITSAN\NsBasetheme\Utility\Tinysource->tinysource'
);
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output'][] =
    'NITSAN\NsBasetheme\Utility\Tinysource->tinysource';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['nsBasetheme_gridToContainerWizard']
    = \NITSAN\NsBasetheme\Updates\GridToContainerWizard::class;
