// Backend Layout for "Content" Template
mod {
    web_layout {
        BackendLayouts {
            content {
                title = LLL:EXT:ns_basetheme/Resources/Private/Language/BackendLayouts/locallang.xlf:content
                icon = EXT:ns_basetheme/Resources/Public/Icons/BackendLayouts/Content.png
                config {
                    backend_layout {
                        colCount = 1
                        rowCount = 2
                        rows {
                            1 {
                                columns {
                                    1 {
                                        name = LLL:EXT:ns_basetheme/Resources/Private/Language/BackendLayouts/locallang.xlf:content
                                        colPos = 0
                                    }
                                }
                            }

                            2 {
                                columns {
                                    1 {
                                        name = LLL:EXT:ns_basetheme/Resources/Private/Language/BackendLayouts/locallang.xlf:footer
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
