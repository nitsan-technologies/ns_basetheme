# Add new custom wizard for our "Components"
mod.wizards.newContentElement.wizardItems.extra {
   
   # Set caption
   header = Custom Components
   icon = 

   # Register each Components
   elements {
      ns_imageteaser {
          iconIdentifier = ns_imageteaser
          title = LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:wizard.ns_imageteaser
          description = LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:wizard.ns_imageteaser.desc
          tt_content_defValues {
              CType = ns_imageteaser
          }
      }
   }
   show := addToList(ns_imageteaser)
}
