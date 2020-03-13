<?php
// TYPO3 Security Check
defined('TYPO3_MODE') or die();

$_EXTKEY = 'ns_basetheme';

//Add Modules
if (TYPO3_MODE === 'BE') {
    $isVersion9Up = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) >= 9000000;

    $GLOBALS['TBE_MODULES'] = array_slice($GLOBALS['TBE_MODULES'], 0, 1, true) +
        ['nitsan' => ''] +
        array_slice($GLOBALS['TBE_MODULES'], 1, count($GLOBALS['TBE_MODULES']) - 1, true);

    if (version_compare(TYPO3_branch, '8.0', '>=')) {
        $GLOBALS['TBE_MODULES']['_configuration']['nitsan'] = [
            'iconIdentifier' => 'module-nsbasetheme',
            'labels' => 'LLL:EXT:ns_basetheme/Resources/Private/Language/BackendModule.xlf',
            'name' => 'nitsan'
        ];
    } else {
        $GLOBALS['TBE_MODULES']['_configuration']['nitsan'] = [
            'iconIdentifier' => 'module-nsbasetheme',
            'labels' => [
                'll_ref' => 'LLL:EXT:ns_basetheme/Resources/Private/Language/BackendModule.xlf'
            ],
            'name' => 'nitsan'
        ];
    }
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'NITSAN.NsBasetheme',
        'nitsan', // Make module a submodule of 'nitsan'
        'nsbasethememodule', // Submodule key
        '', // Position
        [
            'NsBasethemeModule' => 'generalSettings, seoSettings, gdprSettings, styleSettings, integrationSettings, aboutExtension, saveConstant',
        ],
        [
            'access' => 'user,group',
            'icon' => 'EXT:ns_basetheme/Resources/Public/Icons/Extension.svg',
            'labels' => 'LLL:EXT:ns_basetheme/Resources/Private/Language/locallang_basethememodule.xlf',
            'navigationComponentId' => ($isVersion9Up ? 'TYPO3/CMS/Backend/PageTree/PageTreeElement' : 'typo3-pagetree'),
            'inheritNavigationComponentFromMainModule' => false
        ]
    );
}

// Add default include static TypoScript (for root page)
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Configuration/TypoScript',
    '[NITSAN] Parent theme'
);
if (version_compare(TYPO3_branch, '9.0', '>')) {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['Backend\Template\Components\ButtonBar']['getButtonsHook'][] = 'NITSAN\NsBasetheme\Hooks\SaveCloseHook->addSaveCloseButton';
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['Backend\Template\Components\ButtonBar']['getButtonsHook'][] = 'NITSAN\NsBasetheme\Hooks\SaveCloseHook->addSaveShowButton';
}
