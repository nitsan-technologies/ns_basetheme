<?php
// TYPO3 Security
defined('TYPO3_MODE') or die();

call_user_func(function () {

    $locallang_db = 'LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:';

    // Get Components from ext_localconf.php
    $allComponents = constant('ALL_COMPONENTS');

    // Let's load pi_flexform
    foreach ($allComponents as $theComponent) {
      $GLOBALS['TCA']['tt_content']['types']['CType']['subtypes_addlist'][$theComponent] = 'pi_flexform';
      \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
         '',
         'FILE:EXT:site_default/Configuration/FlexForms/'. $theComponent .'.xml',
         ''. $theComponent .''
      );
    }

    // Let's add each Component as CType
    foreach($allComponents as $theComponent) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
            'tt_content',
            'CType',
            [
                'LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:wizard.'.$theComponent,
                $theComponent,
                'content-image'
            ],
            'header',
            'after'
        );
    }

    // Register icon of each component
    $typeIcon = $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['textmedia'];
    foreach($allComponents as $theComponent) {
        $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes'][$theComponent] = $typeIcon;
    }

    // CType: ns_imageteaser
    $ns_imageteaser = [
        'showitem' => '
            --palette--;' . $locallang_db . 'palette.general;general,
            --palette--;;visibility,
            --palette--;' . $locallang_db . 'tca.tab.elements;,pi_flexform,
            --div--;' . $locallang_db . 'palette.access,
            --palette--;' . $locallang_db . 'palette.access;access,
        ',
        'columnsOverrides' => [
            'bodytext' => [
                'config' => [
                    'enableRichtext' => 1,
                    'richtextConfiguration' => 'default'
                ]
            ]
        ]
    ];

    // CType: ns_slider
    $ns_slider = [
        'showitem' => '
            --palette--;' . $locallang_db . 'palette.general;general,
            --palette--;;visibility,
            --palette--;' . $locallang_db . 'tca.tab.elements;,pi_flexform,
            --div--;' . $locallang_db . 'palette.access,
            --palette--;' . $locallang_db . 'palette.access;access,
        ',
    ];

	// Adding each components
    foreach($allComponents as $theComponent) {
        $GLOBALS['TCA']['tt_content']['types'][$theComponent] = $$theComponent;
    }
});
