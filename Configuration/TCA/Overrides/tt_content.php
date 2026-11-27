<?php

declare(strict_types=1);

defined('TYPO3') or die();

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

call_user_func(function () {
    $locallang_db = '';

    // Let's load all the components
    if (defined('ALL_COMPONENTS')) {
        // Get Components from ext_localconf.php
        $allComponents = constant('ALL_COMPONENTS');

        // Check if Content Blocks extension is loaded
        $contentBlocksLoaded = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('content_blocks');

        // Get list of Content Block identifiers by checking for config.yaml files
        // Content Blocks expects: ContentBlocks/ContentElements/{block-name}/config.yaml
        // This avoids dependency injection issues at TCA load time
        $contentBlockIdentifiers = [];
        if ($contentBlocksLoaded) {
            foreach ($allComponents as $extKey => $extValue) {
                foreach ($extValue as $key => $theComponent) {
                    // Check if Content Block config.yaml exists for this component
                    // Correct path: ContentBlocks/ContentElements/{block-name}/config.yaml
                    $contentBlockConfigPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName(
                        'EXT:' . $extKey . '/ContentBlocks/ContentElements/' . $theComponent . '/config.yaml'
                    );
                    if ($contentBlockConfigPath && file_exists($contentBlockConfigPath)) {
                        // Try to read identifier from config.yaml
                        $configContent = @file_get_contents($contentBlockConfigPath);
                        if ($configContent && preg_match('/identifier:\s*([a-zA-Z0-9_]+)/', $configContent, $matches)) {
                            $contentBlockIdentifiers[] = $matches[1];
                        } else {
                            // Fallback: use component name as identifier
                            $contentBlockIdentifiers[] = $theComponent;
                        }
                    }
                }
            }
        }

        // Let's add each Component as CType
        // Note: Content Blocks automatically register their CTypes, so we skip them here
        foreach ($allComponents as $extKey => $extValue) {
            foreach ($extValue as $key => $theComponent) {
                // Skip if this is a Content Block - Content Blocks handle their own CType registration
                if (in_array($theComponent, $contentBlockIdentifiers)) {
                    continue;
                }

                \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
                    'tt_content',
                    'CType',
                    [
                        'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xlf:wizard.' . $theComponent,
                        $theComponent,
                        'content-image',
                    ],
                    'header',
                    'after'
                );
            }
        }

        // Register icon of each component
        // Note: Content Blocks handle their own icons, so we skip them here
        $typeIcon = $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['textmedia'];
        foreach ($allComponents as $extKey => $extValue) {
            foreach ($extValue as $key => $theComponent) {
                // Skip if this is a Content Block - Content Blocks handle their own icons
                if (in_array($theComponent, $contentBlockIdentifiers)) {
                    continue;
                }

                $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes'][$theComponent] = $typeIcon;
            }
        }

        // Adding each components (FlexForm via columnsOverrides — replaces deprecated addPiFlexFormValue)
        // Note: Content Blocks handle their own TCA configuration, so we skip them here
        foreach ($allComponents as $extKey => $extValue) {
            foreach ($extValue as $key => $theComponent) {
                // Skip if this is a Content Block - Content Blocks handle their own TCA
                if (in_array($theComponent, $contentBlockIdentifiers)) {
                    continue;
                }

                $flexFormDs = 'FILE:EXT:' . $extKey . '/Configuration/FlexForms/' . $theComponent . '.xml';
                $GLOBALS['TCA']['tt_content']['types'][$theComponent] = [
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
                    'columnsOverrides' => [
                        'pi_flexform' => [
                            'config' => [
                                'ds' => $flexFormDs,
                            ],
                        ],
                    ],
                ];
            }
        }

        $imageManipulation = [
            'image' => 'image',
            'textmedia' => 'assets',
            'textpic' => 'image',
        ];

        $imgLl = 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:';

        foreach ($imageManipulation as $key => $value) {
            $GLOBALS['TCA']['tt_content']['types'][$key]['columnsOverrides'][$value]['config']['overrideChildTca']['columns']['crop']['config'] = [
                'type' => 'imageManipulation',
                'cropVariants' => [
                    'specialMobile' => [
                        'title' => 'ns_basetheme.db:imageManipulation.mobile',
                        'allowedAspectRatios' => [
                            'NaN' => [
                                'title' => $imgLl . 'imwizard.ratio.free',
                                'value' => 0.0,
                            ],
                        ],
                    ],
                    'specialTablet' => [
                        'title' => 'ns_basetheme.db:imageManipulation.tablet',
                        'allowedAspectRatios' => [
                            'NaN' => [
                                'title' => $imgLl . 'imwizard.ratio.free',
                                'value' => 0.0,
                            ],
                        ],
                    ],
                    'default' => [
                        'title' => 'ns_basetheme.db:imageManipulation.desktop',
                        'allowedAspectRatios' => [
                            'NaN' => [
                                'title' => $imgLl . 'imwizard.ratio.free',
                                'value' => 0.0,
                            ],
                        ],
                    ],
                ],
            ];
        }
    }

    // Special code for EXT.container (if installed)
    if (TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('container')) {
        \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\B13\Container\Tca\Registry::class)->configureContainer(
            (
                new \B13\Container\Tca\ContainerConfiguration(
                    'ns_base_container', // CType
                    'ns_basetheme.db:grid.container', // label
                    'ns_basetheme.db:grid.container.des', // description
                    [
                        [
                            ['name' => 'ns_basetheme.db:grid.content', 'colPos' => 101],
                        ],
                    ] // grid configuration
                )
            )
            // set an optional icon configuration
            ->setIcon('EXT:ns_basetheme/Resources/Public/Icons/Container/container.svg')
            ->setSaveAndCloseInNewContentElementWizard(false)
        );
        \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\B13\Container\Tca\Registry::class)->configureContainer(
            (
                new \B13\Container\Tca\ContainerConfiguration(
                    'ns_base_2Cols', // CType
                    'ns_basetheme.db:grid.2column', // label
                    'ns_basetheme.db:grid.2column.des', // description
                    [
                        [
                            ['name' => 'ns_basetheme.db:grid.content', 'colPos' => 101],
                            ['name' => 'ns_basetheme.db:grid.content', 'colPos' => 102],
                        ],
                    ] // grid configuration
                )
            )
            // set an optional icon configuration
            ->setIcon('EXT:ns_basetheme/Resources/Public/Icons/Container/container-2col.svg')
            ->setSaveAndCloseInNewContentElementWizard(false)
        );
        \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\B13\Container\Tca\Registry::class)->configureContainer(
            (
                new \B13\Container\Tca\ContainerConfiguration(
                    'ns_base_3Cols', // CType
                    'ns_basetheme.db:grid.3column', // label
                    'ns_basetheme.db:grid.3column.des', // description
                    [
                        [
                            ['name' => 'ns_basetheme.db:grid.content', 'colPos' => 101],
                            ['name' => 'ns_basetheme.db:grid.content', 'colPos' => 102],
                            ['name' => 'ns_basetheme.db:grid.content', 'colPos' => 103],
                        ],
                    ] // grid configuration
                )
            )
            // set an optional icon configuration
            ->setIcon('EXT:ns_basetheme/Resources/Public/Icons/Container/container-3col.svg')
            ->setSaveAndCloseInNewContentElementWizard(false)
        );

        \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\B13\Container\Tca\Registry::class)->configureContainer(
            (
                new \B13\Container\Tca\ContainerConfiguration(
                    'ns_base_4Cols', // CType
                    'ns_basetheme.db:grid.4column', // label
                    'ns_basetheme.db:grid.4column.des', // description
                    [
                        [
                            ['name' => 'ns_basetheme.db:grid.content', 'colPos' => 101],
                            ['name' => 'ns_basetheme.db:grid.content', 'colPos' => 102],
                            ['name' => 'ns_basetheme.db:grid.content', 'colPos' => 103],
                            ['name' => 'ns_basetheme.db:grid.content', 'colPos' => 104],
                        ],
                    ] // grid configuration
                )
            )
            // set an optional icon configuration
            ->setIcon('EXT:ns_basetheme/Resources/Public/Icons/Container/container-4col.svg')
            ->setSaveAndCloseInNewContentElementWizard(false)
        );

        // Let's check if Bootstrap version in EXT.ns_theme_mugele
        $nsbasethemeUtility = GeneralUtility::makeInstance(\NITSAN\NsBasetheme\NsBasethemeUtility::class);
        $installedTheme = $nsbasethemeUtility->getInstalledChildTheme();

        $checkFile = '';
        if ($installedTheme) {
            if (Environment::isComposerMode()) {
                $installedTheme = str_replace('_', '-', $installedTheme);
                $basePath = Environment::getProjectPath() . '/vendor/nitsan/' . $installedTheme[0] . '/Resources/Public/CheckBootstrapVersion';
            } else {
                $basePath = Environment::getPublicPath() . '/typo3conf/ext/' . $installedTheme[0] . '/Resources/Public/CheckBootstrapVersion';
            }
            $checkFile = @file_exists($basePath);
        }
        $CheckBootstrapVersion = '';
        if ($checkFile) {
            $CheckBootstrapVersion = file_get_contents($basePath);
        }
        $grids = ['ns_base_2Cols', 'ns_base_3Cols', 'ns_base_4Cols'];
        foreach ($grids as $grid) {
            $flexFormFolder = $CheckBootstrapVersion == 'Bootstrap5'
                ? 'Container_Bootstrap5'
                : 'Container';
            $flexFormDs = 'FILE:EXT:ns_basetheme/Configuration/FlexForms/' . $flexFormFolder . '/' . $grid . '.xml';

            $GLOBALS['TCA']['tt_content']['types'][$grid]['columnsOverrides']['pi_flexform']['config']['ds'] = $flexFormDs;
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
