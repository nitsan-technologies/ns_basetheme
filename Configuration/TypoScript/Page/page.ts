// Grab all the constant
plugin {
    ns_basetheme {
        settings {
            logo = {$ns_basetheme.website.settings.logo}
            copyright = {$ns_basetheme.website.settings.copyright}
        }
    }
}

// Initiate Page Object
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
    }
}

# Define the library
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
}

# <body> Setup common classes
page.bodyTagCObject = COA
page.bodyTagCObject {
    wrap = <body class="|">

    10 = COA
    10 {
        # page uid
        10 = TEXT
        10.field = alias // uid
        10.wrap = id_|

        # tree level (NOTE: 0 is first level, ID=1)
        20 = TEXT
        20.data = level : 1
        20.noTrimWrap = | tree||

        # parent uid
        30 = TEXT
        30.field = pid
        30.noTrimWrap = | parent||

        # language uid
        40 = TEXT
        40.data = TSFE : sys_language_uid
        40.noTrimWrap = | lang| |
    }
}