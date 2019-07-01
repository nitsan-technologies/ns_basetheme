// Let's initiate and setup default configuration for EXT:indexed_search

plugin.tx_indexedsearch {
    settings {
        displayRules = 0
        displayAdvancedSearchLink = 0
        displayResultNumber = 0

        results {
            titleCropAfter = 50
            titleCropSignifier = ...
            summaryCropAfter = 180
            summaryCropSignifier =
            hrefInSummaryCropAfter = 60
            hrefInSummaryCropSignifier = ...
            markupSW_summaryMax = 300
            markupSW_postPreLgd = 60
            markupSW_postPreLgd_offset = 5
            markupSW_divider = ...
            markupSW_divider.noTrimWrap = | | |
        }

        blind {
            searchType = 0
            defaultOperand = 0
            sections = 0
            freeIndexUid = 1
            mediaType = 0
            sortOrder = 0
            group = 0
            languageUid = 0
            desc = 0
            # List of available number of results. First will be used as default.
            numberOfResults = 10,25,50,100
            # defaultOperand.1 = 1
            # extResume=1
        }
    }

    view {
        templateRootPaths {
            10 = EXT:ns_basetheme/Resources/Private/Extensions/IndexedSearch/Templates
        }

        partialRootPaths {
            10 = EXT:ns_basetheme/Resources/Private/Extensions/IndexedSearch/Partials
        }

        layoutRootPaths {
            10 = EXT:ns_basetheme/Resources/Private/Extensions/IndexedSearch/Layouts
        }
    }
}

# [globalVar = GP:L=1]
#    plugin.tx_indexedsearch.settings.defaultOptions.languageUid = 1
# [global]
# [globalVar = GP:L=0]
#    plugin.tx_indexedsearch.settings.defaultOptions.languageUid = 0
# [global]