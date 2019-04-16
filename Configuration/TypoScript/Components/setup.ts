// Initiate fluid styled content and change the templates/partial paths
lib.fluidContent < lib.contentElement
lib.fluidContent {
    templateRootPaths {
        100 = {$plugin.tx_nitsan_pi1.view.templateRootPath}
    }
    partialRootPaths {
        100 = {$plugin.tx_nitsan_pi1.view.partialRootPath}
    }
    variables {       
    }
}

// Prepare data and files processor
tt_content {

    // Initiate default content
    ns_default < lib.fluidContent
    ns_default {
      templateName = NsDefault
      dataProcessing {
            10 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
            10 {
                references.fieldName = image
                as = image
            }            
            20 = NITSAN\site_default\DataProcessing\DefaultProcessor
        }
    }

    // Prepare each Component
    ns_imageteaser < .ns_default
    ns_imageteaser.templateName = Ns_imageteaser
}