# # Default

mod {
    web_layout {
        BackendLayouts {
            default {
                title = LLL:EXT:site_default/Resources/Private/Language/BackendLayouts/locallang.xlf:default
                icon = EXT:site_default/Resources/Public/Icons/BackendLayouts/default.png
                config {
					backend_layout {
						colCount = 1
						rowCount = 4
						rows {
							1 {
								columns {
									1 {
										name = LLL:EXT:site_default/Resources/Private/Language/BackendLayouts/locallang.xlf:header
										colPos = 10
									}
								}
							}
							2 {
								columns {
									1 {
										name = LLL:EXT:site_default/Resources/Private/Language/BackendLayouts/locallang.xlf:teaser
										colPos = 0
									}
								}
							}
							3 {
								columns {
									1 {
										name = LLL:EXT:site_default/Resources/Private/Language/BackendLayouts/locallang.xlf:content
										colPos = 1
									}
								}
							}
							4 {
								columns {
									1 {
										name = LLL:EXT:site_default/Resources/Private/Language/BackendLayouts/locallang.xlf:footer
										colPos = 20
									}
								}
							}
						}
					}
                }
            }
        }
    }
}
