################
### CONSTANT ###
################

plugin {
    ns_basetheme {
        settings {
            logo = {$ns_basetheme.website.settings.logo}
            rootpage = {$ns_basetheme.website.settings.rootpage}
            copyright = {$ns_basetheme.website.settings.copyright}
        }
    }
}

# Module configuration
module.tx_nsbasetheme {
    view {
        templateRootPaths.0 = EXT:ns_basetheme/Resources/Private/Backend/Templates/
        partialRootPaths.0 = EXT:ns_basetheme/Resources/Private/Backend/Partials/
        layoutRootPaths.0 = EXT:ns_basetheme/Resources/Private/Backend/Layouts/
    }
}

########################
### MAIN PAGE OBJECT ###
########################

page = PAGE
page {

    // Set default page typenum
    typeNum = 0

    // Setup favion
    shortcutIcon = typo3conf/exit/ns_basetheme/Resources/Public/images/favicon.ico

    // Set viewport
    meta {
        viewport = width=device-width,initial-scale=1, maximum-scale=1, user-scalable=no
    }

    // Modify Google Analytics from CS_SEO
    headerData {
        998 = FLUIDTEMPLATE
        998 {
            file = EXT:ns_basetheme/Resources/Private/Extensions/Cookie/Layouts/Cookie.html

            templateRootPath = EXT:ns_basetheme/Resources/Private/Extensions/Cookie/Templates/
            partialRootPath = EXT:ns_basetheme/Resources/Private/Extensions/Cookie/Partials/
            layoutRootPath = EXT:ns_basetheme/Resources/Private/Extensions/Cookie/Layouts/

            settings {
                url = {$ns_basetheme.website.settings.cookie.settings.url}
                theme = {$ns_basetheme.website.settings.cookie.settings.theme}
                position = {$ns_basetheme.website.settings.cookie.settings.position}
                type = {$ns_basetheme.website.settings.cookie.settings.type}
                dismissOnScroll = {$ns_basetheme.website.settings.cookie.settings.dismissOnScroll}
                barBg = {$ns_basetheme.website.settings.cookie.settings.palette.popup.background}
                barColor = {$ns_basetheme.website.settings.cookie.settings.palette.popup.text}
                btnBg = {$ns_basetheme.website.settings.cookie.settings.palette.button.background}
                btnColor = {$ns_basetheme.website.settings.cookie.settings.palette.button.text}
            }
        }

        657 {
            10 {
                stdWrap.replacement {
                    10 {
                        search = <script
                        replace = <script data-ignore="1" data-cookieconsent="statistics" type="text/plain"
                    }
                    20 {
                        search = src=
                        replace = data-src=
                    }
                }
            }
            # Modify Google Analytics from CS_SEO
            20 {
                stdWrap.replacement {
                    10 {
                        search = <script
                        replace = <script data-ignore="1" data-cookieconsent="statistics" type="text/plain"
                    }
                }
            }
        }

        // Modify Google Tag-Manager & Piwiki from CS_SEO
        90 = COA
        90 {
            wrap = <script data-ignore="1" data-cookieconsent="statistics" type="text/plain">|</script>
            10 < .jsInline.654
        }
        987 = TEXT
        987.value (
            <script>
              (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
              (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
              m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
              })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

              ga('create', '{$ns_basetheme.website.settings.googleanalytics}', 'auto');
              ga('send', 'pageview');
            </script>
        )
    }

    bodyTagCObject = COA
    bodyTagCObject {
        wrap = <body class="|">

        10 = COA
        10 {
            # page uid
            10 = TEXT
            10 {
                field = alias // uid
                wrap = id_|
            }

            # tree level (NOTE: 0 is first level, ID=1)
            20 = TEXT
            20 {
                data = level : 1
                noTrimWrap = | tree||
            }

            # parent uid
            30 = TEXT
            30 {
                field = pid
                noTrimWrap = | parent||
            }

            # language uid
            40 = TEXT
            40 {
                data = TSFE : sys_language_uid
                noTrimWrap = | lang| |
            }
        }
    }

    // Initiate all the css-together
    includeCSS {
        10 = typo3conf/ext/ns_basetheme/Resources/Public/vendor/bootstrap/css/bootstrap.min.css
        20 = typo3conf/ext/ns_basetheme/Resources/Public/css/custom.css
    }

    // Initiate all the js-together
    includeJSFooter {
        10 = typo3conf/ext/ns_basetheme/Resources/Public/vendor/jquery/jquery.min.js
        20 = typo3conf/ext/ns_basetheme/Resources/Public/vendor/bootstrap/js/bootstrap.bundle.min.js
    }

    // Let's start fluid_styled_content
    10 = FLUIDTEMPLATE
    10 {
        layoutRootPath = {$ns_basetheme.website.paths.layoutRootPath}
        partialRootPath = {$ns_basetheme.website.paths.partialRootPath}
        templateRootPath = {$ns_basetheme.website.paths.templateRootPath}

        // Let's automatically choose backend layout and template
        file.stdWrap.cObject = CASE
        file.stdWrap.cObject {
            key {
                data = levelfield:-1, backend_layout_next_level, slide
                override.field = backend_layout
            }

            default = TEXT
            default.value = {$ns_basetheme.website.paths.templateRootPath}Default.html

            pagets__content = TEXT
            pagets__content.value = {$ns_basetheme.website.paths.templateRootPath}Content.html
        }

        settings < plugin.ns_basetheme.settings

        // Generate menu with DataProcessing
        dataProcessing {

            // Main menu
            101 = TYPO3\CMS\Frontend\DataProcessing\MenuProcessor
            101 {
                levels = 5
                special = directory
                special.value = {$ns_basetheme.website.settings.main_menu}
                expandAll = 1
                includeSpacer = 1
                as = MainMenu
            }

            // Footer menu
            102 = TYPO3\CMS\Frontend\DataProcessing\MenuProcessor
            102 {
                levels = 5
                special = directory
                special.value = {$ns_basetheme.website.settings.footer_menu}
                expandAll = 1
                includeSpacer = 1
                as = FooterMenu
            }
        }
    }
}

#########################
### GLOBAL LIB OBJECT ###
#########################

lib {
    // Define default content
    content = CONTENT
    content < styles.content.get

    // Footer's dynamic copyright text
    copyright = COA
    copyright {
        stdWrap.wrap = @|

        1 = TEXT
        1 {
            current = 1
            strftime = %Y
            wrap = &nbsp;|&nbsp;
        }

        2 = TEXT
        2 {
            value = {$ns_basetheme.website.settings.copyright}
            wrap = |
        }
    }

    // Get rootPageId
    rootPageId = TEXT
    rootPageId {
        data = leveluid : 0
    }
}

ajaxData = PAGE
ajaxData {
    typeNum = 22184356
    config {
        disableAllHeaderCode = 1
        additionalHeaders = Content-type:application/html
        xhtml_cleaning = 0
        debug = 0
        no_cache = 1
        admPanel = 0
    }
}
