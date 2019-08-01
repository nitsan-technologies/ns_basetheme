// Initiate Page Object
page = PAGE
page {

    // Set default page typenum
    typeNum = 0

    // Setup body tag with page-id
    bodyTag >
    bodyTagCObject = TEXT
    bodyTagCObject {
        value = <body class='' id='page_{page:uid}'>
        insertData = 1
    }

    // Setup favion
    favicon = typo3conf/exit/ns_basetheme/Resources/Public/images/favicon.ico

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

        settings < plugin.tx_nsBasetheme.settings
    }
}

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

    //Get Testimonial content
    getTestimonials = CONTENT
    getTestimonials {
        table = tx_nsthemens2019_domain_model_testimonial
        select {
            pidInList = 0
            uidInList = {field:uid}
            uidInList.insertData = 1
            andWhere = hidden = 0 && deleted = 0
            selectFields = *
        }
        renderObj = COA
        renderObj {
            wrap = <div class="lqd-column col-md-4 col-sm-6">|</div>

            #Render video file/link    
            5 = FILES
            5 {
                references {
                    uid.field = uid
                    fieldName = youtube_link
                }
                renderObj = COA
                renderObj {
                    5 = TEXT
                    5.value = <a href="
                    10 = TEXT
                    10 {
                        data = file:current:publicUrl
                        wrap = |" class="fresco">
                    }

                }
            }
            #Render Image file 
            10 = FILES
            10 {
                references {
                    uid.field = uid
                    fieldName = image
                }
                renderObj = IMAGE
                renderObj {
                    file.import.data = file:current:uid
                    file.treatIdAsReference = 1
                    file.width = 570
                    file.height = 460
                    altText.data = file:current:title
                    wrap = <div class="fancy-box fancy-box-classes fancy-box-heading-sm"><figure class="fancy-box-image">|</figure>
                }
            }
            20 = TEXT
            20.wrap = <div class="fancy-box-contents"><div class="fancy-box-info">|

            30 = TEXT
            30 {
                field = title
                stdWrap.htmlSpecialChars = 0
                wrap = <h3 class="font-weight-semibold">|</h3>
            }

            40 = TEXT
            40 {
                field = detail
                stdWrap.htmlSpecialChars = 0
                wrap = <span class="trainer"><i class="icon-md-play-circle"></i>|</span>
            }

            50 = TEXT
            50.wrap = |</div></div></div></a>
        }
    }

}
