lib.fluidContent {
	
	templateRootPaths {
		0 = EXT:site_default/Resources/Private/Extensions/fluid_styled_content/Templates/
		10 = {$styles.templates.templateRootPath}
	}
	partialRootPaths {
		0 = EXT:site_default/Resources/Private/Extensions/fluid_styled_content/Partials/
		10 = {$styles.templates.partialRootPath}
	}
	layoutRootPaths {
		0 = EXT:site_default/Resources/Private/Extensions/fluid_styled_content/Layouts/
		10 = {$styles.templates.layoutRootPath}
	}
}



# Regular Text Element:
# A regular text element with header and bodytext fields.
#
# CType: custom

tt_content {
    imagestext < lib.fluidContent
    imagestext {
   	  templateName = ImagesText
      dataProcessing {
         10 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
         10 {
            references.fieldName = assets
         }
         20 = TYPO3\SiteDefault\DataProcessing\ImagesTextProcessor
      }
    }

    contentBrochure < lib.fluidContent
    contentBrochure {
        templateName = ContentBrochure
        dataProcessing {
            10 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
            10 {
                references.fieldName = assets
            }
            20 = TYPO3\SiteDefault\DataProcessing\OwlCarouselSliderProcessor
        }
    }

    contentHeadline < lib.fluidContent
    contentHeadline {
        templateName = ContentHeadline
        dataProcessing {
            10 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
            10 {
                references.fieldName = assets
            }
            20 = TYPO3\SiteDefault\DataProcessing\OwlCarouselSliderProcessor
        }
    }


    accordeon < lib.fluidContent
    accordeon {
      templateName = Accordeon
      dataProcessing {
            20 = RSM\Rsmnbbcontent\DataProcessing\DefaultProcessor
        }
    }
}

