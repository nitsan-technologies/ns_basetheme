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
                    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                        --palette--;;frames,
                        --palette--;;appearanceLinks,
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                        --palette--;;language,
                    --div--;LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xlf:palette.access,
                    --palette--;LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xlf:palette.access;access,
                ',
            ];
            $GLOBALS['TCA']['tt_content']['types'][$theComponent] = $tcaComponent;
        }
    }
    
    $imageManipulation = [
        'image' => 'image',
        'textmedia' => 'assets',
        'textpic' => 'image'
    ];

    foreach ($imageManipulation as $key => $value) {
        $GLOBALS['TCA']['tt_content']['types'][$key]['columnsOverrides'][$value]['config']['overrideChildTca']['columns']['crop']['config'] = [
            'type' => 'imageManipulation',
            'cropVariants' => [
                'specialMobile' => [
                    'title' => 'LLL:EXT:ns_basetheme/Resources/Private/Language/locallang_db.xlf:imageManipulation.mobile',
                    'allowedAspectRatios' => [
                        'NaN' => [
                            'title' => 'LLL:EXT:lang/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.free',
                            'value' => 0.0
                        ],
                    ],
                ],
                'specialTablet' => [
                    'title' => 'LLL:EXT:ns_basetheme/Resources/Private/Language/locallang_db.xlf:imageManipulation.tablet',
                    'allowedAspectRatios' => [
                        'NaN' => [
                            'title' => 'LLL:EXT:lang/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.free',
                            'value' => 0.0
                        ],
                    ],
                ],
                'default' => [
                    'title' => 'LLL:EXT:ns_basetheme/Resources/Private/Language/locallang_db.xlf:imageManipulation.desktop',
                    'allowedAspectRatios' => [
                        'NaN' => [
                            'title' => 'LLL:EXT:lang/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.free',
                            'value' => 0.0
                        ],
                    ],
                ],
            ]
        ];
    }
});
