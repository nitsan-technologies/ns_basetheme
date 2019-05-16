<?php
// TYPO3 Security
defined('TYPO3_MODE') or die();

call_user_func(function () {

    $locallang_db = '';

    // Get Components from ext_localconf.php
    $allComponents = constant('ALL_COMPONENTS');

    // Let's load pi_flexform
    foreach ($allComponents as $extKey => $extValue) {
        foreach ($extValue as $key => $theComponent) {
            $GLOBALS['TCA']['tt_content']['types']['CType']['subtypes_addlist'][$theComponent] = 'pi_flexform';
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
                '',
                'FILE:EXT:' . $extKey . '/Configuration/FlexForms/' . $theComponent . '.xml',
                '' . $theComponent . ''
            );
        }
    }

    // Let's add each Component as CType
    foreach ($allComponents as $extKey => $extValue) {
        foreach ($extValue as $key => $theComponent) {
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
                'tt_content',
                'CType',
                [
                    'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xlf:wizard.' . $theComponent,
                    $theComponent,
                    'content-image'
                ],
                'header',
                'after'
            );
        }
    }

    // Register icon of each component
    $typeIcon = $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['textmedia'];
    foreach ($allComponents as $extKey => $extValue) {
        foreach ($extValue as $key => $theComponent) {
            $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes'][$theComponent] = $typeIcon;
        }
    }

    // Adding each components
    foreach ($allComponents as $extKey => $extValue) {
        foreach ($extValue as $key => $theComponent) {
            $tcaComponent = [
                'showitem' => '
                    --palette--;LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xlf:palette.general;general,
                    --palette--;;visibility,
                    --palette--;LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xlf:tca.tab.elements;,pi_flexform,
                    --div--;LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xlf:palette.access,
                    --palette--;LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xlf:palette.access;access,
                ',
            ];
            $GLOBALS['TCA']['tt_content']['types'][$theComponent] = $tcaComponent;
        }
    }
});
