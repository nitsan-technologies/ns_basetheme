# Add new custom wizard for our "Components"
mod.wizards.newContentElement.wizardItems.extra {
   
   # Set caption
   header = Custom Components
   icon = 

   # Register each Components
   elements {

      # CType: Image Teaser
      ns_imageteaser {
          iconIdentifier = ns_imageteaser
          title = LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:wizard.ns_imageteaser
          description = LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:wizard.ns_imageteaser.desc
          tt_content_defValues {
              CType = ns_imageteaser
          }
      }

      # CType: Slider
      ns_slider {
            iconIdentifier = ns_slider
            title = LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:wizard.ns_slider
            description = LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:wizard.ns_slider.desc
            tt_content_defValues {
                CType = ns_slider
            }
      }
   }
   show := addToList(ns_imageteaser, ns_slider)
}
