
############################
### CUSTOM SUBCATEGORIES ###
###########################
# customsubcategory=100=LLL:EXT:ns_basetheme/Resources/Private/Language/locallang_db.xlf:website
# customsubcategory=110=LLL:EXT:ns_basetheme/Resources/Private/Language/locallang_db.xlf:cookie

# Let's define some constants for global configuration
ns_basetheme {
    website {
        settings {
            #cat = ns_basetheme/100/01; type=string; label=Logo Path
            logo = typo3conf/ext/ns_basetheme/Resources/Public/Images/Logo.png

            #cat = ns_basetheme/100/02; type=boolean; label=Cache enable/disable
            no_cache = 1

            #cat = ns_basetheme/100/03; type=string; label=Copyright Text
            copyright = Copyright

            #cat = ns_basetheme/100/04; type=int; label=Main Menu ID
            main_menu = 11

            #cat = ns_basetheme/100/05; type=int; label=Footer Menu ID
            footer_menu = 6

            #cat = ns_basetheme/100/06; type=string; label=Root Page Id
            rootpage = 1

            #cat = ns_basetheme/100/07; type=boolean; label=Compress and Concatenate CSS/JS
            compress_cssjs = 0

            #cat = ns_basetheme/100/08; type=string; label=Google Analytics Id
            googleanalytics =

            cookie {

                settings {
                    # cat=ns_basetheme/110; type=int+; label= PID to Data Protection
                    url =
                    # cat=ns_basetheme/110; type=options[edgeless,classic,basic]; label=Layout
                    theme = edgeless
                    # cat=ns_basetheme/110; type=options[top,top-left,top-right,bottom,bottom-left,bottom-right]; label= Position
                    position = bottom-right
                    # cat=ns_basetheme/110; type=int+; label= dismiss on scroll (in PX)
                    dismissOnScroll =
                    # cat=ns_basetheme/110; type=options[info,opt-out]; label = Type
                    type = info

                    palette {
                        popup {
                            # cat=ns_basetheme/110/popup; type=color; label= Bar: Background
                            background = rgba(0,0,0,.8)
                            # cat=ns_basetheme/110/popup; type=color; label= Bar: Text
                            text = #fff
                        }
                        button {
                            # cat=ns_basetheme/110/button; type=color; label= Button: Background
                            background = #b81839
                            # cat=ns_basetheme/110/button; type=color; label= Button: Text
                            text = #fff
                        }
                    }
                }
            }
        }

        # Define paths for templates, layout and partial
        paths {
            templateRootPath = typo3conf/ext/ns_basetheme/Resources/Private/Templates/
            layoutRootPath = typo3conf/ext/ns_basetheme/Resources/Private/Layouts/
            partialRootPath = typo3conf/ext/ns_basetheme/Resources/Private/Partials/
        }
    }
}