// Backend Layout for "Default" Template

mod {
    web_layout {
        BackendLayouts {
            default {
                title = LLL:EXT:site_default/Resources/Private/Language/BackendLayouts/locallang.xlf:default
                icon = EXT:site_default/Resources/Public/Icons/BackendLayouts/Default.png
                config {
					backend_layout {
						colCount = 1
						rowCount = 2
						rows {
							1 {
								columns {
									1 {
										name = LLL:EXT:site_default/Resources/Private/Language/BackendLayouts/locallang.xlf:content
										colPos = 0
									}
								}
							}
							2 {
								columns {
									1 {
										name = LLL:EXT:site_default/Resources/Private/Language/BackendLayouts/locallang.xlf:footer
										colPos = 1
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
