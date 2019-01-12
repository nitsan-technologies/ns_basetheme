<?php
defined('TYPO3_MODE') or die();

call_user_func(function () {

    $customLanguageFilePrefix = 'LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:';

    /* Types */
    $typeArray = [
        'ns_text',
        'ns_image',
        'ns_serviceteaser',
        'ns_imageteaser',
    ];

    /* add to TCA */
    foreach($typeArray as $currentType) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
            'tt_content',
            'CType',
            [
                'LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:wizard.'.$currentType,
                $currentType,
                'content-image'
            ],
            'header',
            'after'
        );
    }

    /* Type Icon */
    $typeIcon = $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['textmedia'];
    foreach($typeArray as $currentType) {
        $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes'][$currentType] = $typeIcon;
    }

    /* Define presets with fields to display */
    $headerAndFlexform = [
        'showitem'         => '
                --palette--;' . $customLanguageFilePrefix . 'palette.general;general,
                --palette--;;visibility,
                --palette--;' . $customLanguageFilePrefix . 'tca.tab.elements;header,sectionIndex,pi_flexform,categories,
                --div--;' . $customLanguageFilePrefix . 'palette.access,
                    --palette--;' . $customLanguageFilePrefix . 'palette.access;access,
        '
    ];
	$headerImageAndFlexform = [
		'showitem'         => '
                --palette--;' . $customLanguageFilePrefix . 'palette.general;general,
                --palette--;;visibility,
                --palette--;' . $customLanguageFilePrefix . 'tca.tab.elements;header,sectionIndex,image,pi_flexform,categories,
                --div--;' . $customLanguageFilePrefix . 'palette.access,
                    --palette--;' . $customLanguageFilePrefix . 'palette.access;access,
        '
	];

	$headerBodyTextFlexformAndImage = [
		'showitem'         => '
                --palette--;' . $customLanguageFilePrefix . 'palette.general;general,
                --palette--;;visibility,
                --palette--;' . $customLanguageFilePrefix . 'tca.tab.elements;header,sectionIndex,image,bodytext,pi_flexform,categories,
                --div--;' . $customLanguageFilePrefix . 'palette.access,
                    --palette--;' . $customLanguageFilePrefix . 'palette.access;access,
        '
	];
    $headerPagesAndCategories = [
        'showitem'         => '
                --palette--;' . $customLanguageFilePrefix . 'palette.general;general,
                --palette--;;visibility,
                --palette--;' . $customLanguageFilePrefix . 'tca.tab.elements;header,pages,categories,
                --div--;' . $customLanguageFilePrefix . 'palette.access,
                    --palette--;' . $customLanguageFilePrefix . 'palette.access;access,
        '
    ];

    $headerBodyTextAndFlexform = [
        'showitem'         => '
                --palette--;' . $customLanguageFilePrefix . 'palette.general;general,
                --palette--;;visibility,
                --palette--;' . $customLanguageFilePrefix . 'tca.tab.elements;header,sectionIndex,bodytext,pi_flexform,categories,
                --div--;' . $customLanguageFilePrefix . 'palette.access,
                    --palette--;' . $customLanguageFilePrefix . 'palette.access;access,
        '
    ];
	$headerAndBodyText = [
		'showitem'         => '
                --palette--;' . $customLanguageFilePrefix . 'palette.general;general,
                --palette--;;visibility,
                --palette--;' . $customLanguageFilePrefix . 'tca.tab.elements;header,sectionIndex,bodytext,categories,
                --div--;' . $customLanguageFilePrefix . 'palette.access,
                    --palette--;' . $customLanguageFilePrefix . 'palette.access;access,
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
	$headerBodyTextAndImage = [
		'showitem'         => '
                --palette--;' . $customLanguageFilePrefix . 'palette.general;general,
                --palette--;;visibility,
                --palette--;' . $customLanguageFilePrefix . 'tca.tab.elements;header,sectionIndex,bodytext,image,categories,
                --div--;' . $customLanguageFilePrefix . 'palette.access,
                    --palette--;' . $customLanguageFilePrefix . 'palette.access;access,
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
	$headerAndImage = [
		'showitem'         => '
                --palette--;' . $customLanguageFilePrefix . 'palette.general;general,
                --palette--;;visibility,
                --palette--;' . $customLanguageFilePrefix . 'tca.tab.elements;header,subheader,sectionIndex,image,categories,
                --div--;' . $customLanguageFilePrefix . 'palette.access,
                    --palette--;' . $customLanguageFilePrefix . 'palette.access;access,
        ',
	];
	$headerImageAndMedia = [
		'showitem'         => '
                --palette--;' . $customLanguageFilePrefix . 'palette.general;general,
                --palette--;;visibility,
                --palette--;' . $customLanguageFilePrefix . 'tca.tab.elements;header,sectionIndex,media,image,categories,
                --div--;' . $customLanguageFilePrefix . 'palette.access,
                    --palette--;' . $customLanguageFilePrefix . 'palette.access;access,
        ',
	];
	$headerImageMediaAndFlexform = [
		'showitem'         => '
                --palette--;' . $customLanguageFilePrefix . 'palette.general;general,
                --palette--;;visibility,
                --palette--;' . $customLanguageFilePrefix . 'tca.tab.elements;header,sectionIndex,media,image,pi_flexform,categories,
                --div--;' . $customLanguageFilePrefix . 'palette.access,
                    --palette--;' . $customLanguageFilePrefix . 'palette.access;access,
        ',
	];
	$headerTextImageMediaAndZoom = [
		'showitem'         => '
                --palette--;' . $customLanguageFilePrefix . 'palette.general;general,
                --palette--;;visibility,
                --palette--;' . $customLanguageFilePrefix . 'tca.tab.elements;header,bodytext,layout,image,media,image_zoom,imageorient,categories,
                --div--;' . $customLanguageFilePrefix . 'palette.access,
                    --palette--;' . $customLanguageFilePrefix . 'palette.access;access,
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
	$headerTextImageAndFlexform = [
		'showitem'         => '
                --palette--;' . $customLanguageFilePrefix . 'palette.general;general,
                --palette--;;visibility,
                --palette--;' . $customLanguageFilePrefix . 'tca.tab.elements;header,bodytext,image,pi_flexform,categories,
                --div--;' . $customLanguageFilePrefix . 'palette.access,
                    --palette--;' . $customLanguageFilePrefix . 'palette.access;access,
        '
	];

    $headerTextImageAndZoom = [
        'showitem'         => '
                --palette--;' . $customLanguageFilePrefix . 'palette.general;general,
                --palette--;;visibility,
                --palette--;' . $customLanguageFilePrefix . 'tca.tab.elements;header,sectionIndex,image,image_zoom,categories,
                --div--;' . $customLanguageFilePrefix . 'palette.access,
                    --palette--;' . $customLanguageFilePrefix . 'palette.access;access,
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

    $serviceTeaser = [
        'showitem'         => '
                --palette--;' . $customLanguageFilePrefix . 'palette.general;general,
                --palette--;;visibility,
                --palette--;' . $customLanguageFilePrefix . 'tca.tab.elements;header,sectionIndex,bodytext,
            --div--;LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:tab.ns_serviceteaser.serviceteaser,pi_flexform
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
    
    $imageTeaser = [
        'showitem'         => '
                --palette--;' . $customLanguageFilePrefix . 'palette.general;general,
                --palette--;;visibility,
                --palette--;' . $customLanguageFilePrefix . 'tca.tab.elements;header,sectionIndex,bodytext,
            --div--;LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:tab.ns_imageteaser.teaser,image,pi_flexform
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



	// assign to Elements
	$GLOBALS['TCA']['tt_content']['types']['ns_text'] = $headerAndBodyText;
    $GLOBALS['TCA']['tt_content']['types']['ns_image'] = $headerTextImageAndZoom;
	$GLOBALS['TCA']['tt_content']['types']['ns_serviceteaser'] = $serviceTeaser;
    $GLOBALS['TCA']['tt_content']['types']['ns_imageteaser'] = $imageTeaser;
});
