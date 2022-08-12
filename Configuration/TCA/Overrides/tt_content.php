<?php
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
// TYPO3 Security
defined('TYPO3_MODE') or die();

call_user_func(function () {
    $locallang_db = '';

    // Let's load all the components
    if (defined('ALL_COMPONENTS')) {
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
                        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,space_before_class,space_after_class,
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

        if (version_compare(TYPO3_branch, '9.0', '>')) {
            $imgLl = 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:';
        } else {
            $imgLl = 'LLL:EXT:lang/Resources/Private/Language/locallang_wizards.xlf:';
        }

        foreach ($imageManipulation as $key => $value) {
            $GLOBALS['TCA']['tt_content']['types'][$key]['columnsOverrides'][$value]['config']['overrideChildTca']['columns']['crop']['config'] = [
                'type' => 'imageManipulation',
                'cropVariants' => [
                    'specialMobile' => [
                        'title' => 'LLL:EXT:ns_basetheme/Resources/Private/Language/locallang_db.xlf:imageManipulation.mobile',
                        'allowedAspectRatios' => [
                            'NaN' => [
                                'title' => $imgLl . 'imwizard.ratio.free',
                                'value' => 0.0
                            ],
                        ],
                    ],
                    'specialTablet' => [
                        'title' => 'LLL:EXT:ns_basetheme/Resources/Private/Language/locallang_db.xlf:imageManipulation.tablet',
                        'allowedAspectRatios' => [
                            'NaN' => [
                                'title' => $imgLl . 'imwizard.ratio.free',
                                'value' => 0.0
                            ],
                        ],
                    ],
                    'default' => [
                        'title' => 'LLL:EXT:ns_basetheme/Resources/Private/Language/locallang_db.xlf:imageManipulation.desktop',
                        'allowedAspectRatios' => [
                            'NaN' => [
                                'title' => $imgLl . 'imwizard.ratio.free',
                                'value' => 0.0
                            ],
                        ],
                    ],
                ]
            ];
        }
    }

    // Special code for EXT.container (if installed)
    if (TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('container')) {
        \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\B13\Container\Tca\Registry::class)->configureContainer(
            (
                new \B13\Container\Tca\ContainerConfiguration(
                    'ns_base_container', // CType
                    'Container Grid', // label
                    'Standard Container grid element', // description
                    [
                        [
                            ['name' => 'Content', 'colPos' => 101]
                        ]
                    ] // grid configuration
                )
            )
            // set an optional icon configuration
            ->setIcon('EXT:ns_basetheme/Resources/Public/Icons/Container/container.svg')
            
        );
        \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\B13\Container\Tca\Registry::class)->configureContainer(
            (
                new \B13\Container\Tca\ContainerConfiguration(
                    'ns_base_2Cols', // CType
                    '2 Column Grid', // label
                    'Standard Container grid element', // description
                    [
                        [
                            ['name' => 'Content', 'colPos' => 101],
                            ['name' => 'Content', 'colPos' => 102]
                        ]
                    ] // grid configuration
                )
            )
            // set an optional icon configuration
            ->setIcon('EXT:ns_basetheme/Resources/Public/Icons/Container/container-2col.svg')
        );
        \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\B13\Container\Tca\Registry::class)->configureContainer(
            (
                new \B13\Container\Tca\ContainerConfiguration(
                    'ns_base_3Cols', // CType
                    '3 Column Grid', // label
                    'Standard 3 Column grid element', // description
                    [
                        [
                            ['name' => 'Content', 'colPos' => 101],
                            ['name' => 'Content', 'colPos' => 102],
                            ['name' => 'Content', 'colPos' => 103]
                        ]
                    ] // grid configuration
                )
            )
            // set an optional icon configuration
            ->setIcon('EXT:ns_basetheme/Resources/Public/Icons/Container/container-3col.svg')
        );

        \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\B13\Container\Tca\Registry::class)->configureContainer(
            (
                new \B13\Container\Tca\ContainerConfiguration(
                    'ns_base_4Cols', // CType
                    '4 Column Grid', // label
                    'Standard 4 Column grid element', // description
                    [
                        [
                            ['name' => 'Content', 'colPos' => 101],
                            ['name' => 'Content', 'colPos' => 102],
                            ['name' => 'Content', 'colPos' => 103],
                            ['name' => 'Content', 'colPos' => 104]
                        ]
                    ] // grid configuration
                )
            )
            // set an optional icon configuration
            ->setIcon('EXT:ns_basetheme/Resources/Public/Icons/Container/container-4col.svg')
        );

        // Let's check if Bootstrap version in EXT.ns_theme_child
        $nsbasethemeUtility = GeneralUtility::makeInstance(\NITSAN\NsBasetheme\NsBasethemeUtility::class);
        $installedTheme = $nsbasethemeUtility->getInstalledChildTheme();
        $basePath =  Environment::getPublicPath().'/typo3conf/ext/'.$installedTheme[0].'/Resources/Public/CheckBootstrapVersion';
        $checkfile = @file_exists($basePath);
        $CheckBootstrapVersion = '';
        if ($checkfile) {
            $CheckBootstrapVersion = file_get_contents($basePath);
        }
        $grids = ['ns_base_2Cols', 'ns_base_3Cols', 'ns_base_4Cols'];
        foreach ($grids as $grid) {
            
            if($CheckBootstrapVersion == 'Bootstrap5'){
                \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
                    '',
                    'FILE:EXT:ns_basetheme/Configuration/FlexForms/Container_Bootstrap5/' . $grid . '.xml',
                    $grid
                );
            }else{
                \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
                    '',
                    'FILE:EXT:ns_basetheme/Configuration/FlexForms/Container/' . $grid . '.xml',
                    $grid
                );
            }

            $GLOBALS['TCA']['tt_content']['types'][$grid]['showitem'] = '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    header,pi_flexform;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header.ALT.div_formlabel,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                    --palette--;;frames,
                    --palette--;;appearanceLinks,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;hidden,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    categories,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    rowDescription,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
                ';
        }
    }
});
