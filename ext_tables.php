<?php
// TYPO3 Security Check
defined('TYPO3_MODE') or die();

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use NITSAN\NsBasetheme\Controller\NsBasethemeModuleController;

$_EXTKEY = 'ns_basetheme';

//Add Modules
if (TYPO3_MODE === 'BE') {
    $isVersion9Up = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) >= 9000000;

    if (version_compare(TYPO3_branch, '8.0', '>=')) {

        // Add module 'nitsan' after 'Web'
        if (!isset($GLOBALS['TBE_MODULES']['nitsan'])) {
            $temp_TBE_MODULES = [];
            foreach ($GLOBALS['TBE_MODULES'] as $key => $val) {
                if ($key == 'web') {
                    $temp_TBE_MODULES[$key] = $val;
                    $temp_TBE_MODULES['nitsan'] = '';
                } else {
                    $temp_TBE_MODULES[$key] = $val;
                }
            }
            $GLOBALS['TBE_MODULES'] = $temp_TBE_MODULES;
            $GLOBALS['TBE_MODULES']['_configuration']['nitsan'] = [
                'iconIdentifier' => 'module-nsbasetheme',
                'labels' => 'LLL:EXT:ns_basetheme/Resources/Private/Language/BackendModule.xlf',
                'name' => 'nitsan'
            ];
        }
        if (version_compare(TYPO3_branch, '11.0', '>=')) {
            $moduleClass = NsBasethemeModuleController::class;
        } else {
            $moduleClass = 'NsBasethemeModule';
        }
        ExtensionUtility::registerModule(
            'NITSAN.NsBasetheme',
            'nitsan', // Make module a submodule of 'nitsan'
            'nsbasethememodule', // Submodule key
            '', // Position
            [
                $moduleClass => 'generalSettings, seoSettings, gdprSettings, styleSettings, integrationSettings, aboutExtension, saveConstant',
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
}

if (version_compare(TYPO3_branch, '9.0', '>')) {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['Backend\Template\Components\ButtonBar']['getButtonsHook'][] = 'NITSAN\NsBasetheme\Hooks\SaveCloseHook->addSaveCloseButton';
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['Backend\Template\Components\ButtonBar']['getButtonsHook'][] = 'NITSAN\NsBasetheme\Hooks\SaveCloseHook->addSaveShowButton';
}

// Add Custom TYPO3 Backend Login Screen
if (empty($GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['backend']['loginBackgroundImage'])) {
    $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['backend']['loginBackgroundImage'] = 'EXT:ns_basetheme/Resources/Public/Images/BackendLogin/TYPO3-Rise-Background-2022.png';
}
