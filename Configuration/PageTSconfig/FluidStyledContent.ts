TCEFORM.pages.backend_layout_next_level.removeItems = -1,
TCEFORM.pages.backend_layout.removeItems = -1,

mod.wizards.newContentElement.wizardItems.extra {

   header = Custom Elements
   icon = 

   elements {
        ns_text {
            iconIdentifier = ns_text
            title = LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:wizard.ns_text
            description = LLL:EXT:NITSANbmelcontent/Resources/Private/Language/locallang_db.xlf:wizard.ns_text_desc
            tt_content_defValues {
                CType = ns_text
            }
        }
        ns_image {
            iconIdentifier = ns_image
            title = LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:wizard.ns_image
            description = LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:wizard.ns_image.desc
            tt_content_defValues {
                CType = ns_image
            }
        }
        ns_imageteaser {
            iconIdentifier = ns_imageteaser
            title = LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:wizard.ns_imageteaser
            description = LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:wizard.ns_imageteaser.desc
            tt_content_defValues {
                CType = ns_imageteaser
            }
        }
        ns_serviceteaser {
            iconIdentifier = ns_serviceteaser
            title = LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:wizard.ns_serviceteaser
            description = LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:wizard.ns_serviceteaser.desc
            tt_content_defValues {
                CType = ns_serviceteaser
            }
        }   
    }
   show := addToList(ns_text,ns_image,ns_serviceteaser,ns_imageteaser)
}


TCEFORM.sys_file_reference.crop.config.cropVariants {
    default {
        title = LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:imageManipulation.desktop
        selectedRatio = NaN
        allowedAspectRatios {
            NaN {
                title = LLL:EXT:lang/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.free
                value = 0.0
            }
        }
    }
    specialTablet {
        title = LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:imageManipulation.tablet
        selectedRatio = NaN
        allowedAspectRatios {
            3:2 {
                title = LLL:EXT:lang/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.3_2
                value = 1.5
            }
        }
    }
    specialMobile {
        title = LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:imageManipulation.mobile
        selectedRatio = NaN
        allowedAspectRatios {
            4:3 {
                title = LLL:EXT:lang/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.4_3
                value = 1.3333333
            }
        }
    }
}
