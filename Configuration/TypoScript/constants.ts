# Let's define some constants for global configuration
ns_basetheme {
    website {
        settings {
            #cat = ns_basetheme/website/settings/01; type=string; label=Logo Path
            logo = typo3conf/ext/ns_basetheme/Resources/Public/Images/Logo.png

            #cat = ns_basetheme/website/settings/02; type=boolean; label=Cache enable/disable
            no_cache = 1

            #cat = ns_basetheme/website/settings/03; type=string; label=Copyright Text
            copyright = Copyright

            #cat = ns_basetheme/website/settings/04; type=int; label=Main Menu ID
            main_menu = 11

            #cat = ns_basetheme/website/settings/05; type=int; label=Footer Menu ID
            footer_menu = 6

            #cat = ns_basetheme/website/settings/06; type=string; label=Root Page Id
            rootpage = 1

            #cat = ns_basetheme/website/settings/07; type=boolean; label=Compress and Concatenate CSS/JS
            compress_cssjs = 0
        }

        # Define paths for templates, layout and partial
        paths {
            templateRootPath = typo3conf/ext/ns_basetheme/Resources/Private/Templates/
            layoutRootPath = typo3conf/ext/ns_basetheme/Resources/Private/Layouts/
            partialRootPath = typo3conf/ext/ns_basetheme/Resources/Private/Partials/
        }
    }
}