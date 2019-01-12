# # Content

mod {
    web_layout {
        BackendLayouts {
            content {
                title = LLL:EXT:site_default/Resources/Private/Language/BackendLayouts/locallang.xlf:content
                icon = EXT:site_default/Resources/Public/Icons/BackendLayouts/content.png
                config {
					backend_layout {
						colCount = 1
						rowCount = 2
						rows {
							1 {
								columns {
									1 {
										name = LLL:EXT:site_default/Resources/Private/Language/BackendLayouts/locallang.xlf:teaser
										colPos = 1
									}
								}
							}
							2 {
								columns {
									1 {
										name = LLL:EXT:site_default/Resources/Private/Language/BackendLayouts/locallang.xlf:content
										colPos = 0
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