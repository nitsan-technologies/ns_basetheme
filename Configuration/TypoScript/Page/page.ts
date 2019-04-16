
// Get constant value
plugin.tx_sitedefault.settings.logo = {$site_default.website.settings.logo}

// Initiate Page Object
page = PAGE
page {

	// Set default page typenum
	typeNum = 0

	// Setup body tag with page-id
	bodyTag >
	bodyTagCObject = TEXT
	bodyTagCObject.value = <body class='' id='page_{page:uid}'>
	bodyTagCObject.insertData = 1

	// Setup favion
	favicon = site_default/Resources/Public/images/favicons/favicon.ico

	// Set viewport
	meta {
		viewport = width=device-width,initial-scale=1, maximum-scale=1, user-scalable=no
	}

	// Initiate all the css-together
	includeCSS {
		10 = typo3conf/ext/site_default/Resources/Public/vendor/bootstrap/css/bootstrap.min.css
		20 = typo3conf/ext/site_default/Resources/Public/css/modern-business.css
	}

	// Initiate all the js-together
	includeJSFooter{
		jquery = typo3conf/ext/site_default/Resources/Public/vendor/jquery/jquery.min.js
		bootstrap = typo3conf/ext/site_default/Resources/Public/vendor/bootstrap/js/bootstrap.bundle.min.js
	}

	// Let's start fluid_styled_content
	10 = FLUIDTEMPLATE
	10 {
		layoutRootPath = {$site_default.website.paths.layoutRootPath}
		partialRootPath = {$site_default.website.paths.partialRootPath}
		templateRootPath = {$site_default.website.paths.templateRootPath}

		// Let's automatically choose backend layout and template
		file.stdWrap.cObject = CASE
		file.stdWrap.cObject {
			key.data = levelfield:-1, backend_layout_next_level, slide
			key.override.field = backend_layout
			
			default = TEXT
			default.value = {$site_default.website.paths.templateRootPath}Default.html

			pagets__content = TEXT
			pagets__content.value = {$site_default.website.paths.templateRootPath}Content.html
		}
		
		settings < plugin.tx_sitedefault.settings
	}
}

// Define default content
lib.content = CONTENT
lib.content < styles.content.get

// Footer's dynamic copyright text
lib.copyright = COA
lib.copyright{
    stdWrap.wrap = @|

    1 = TEXT
    1.current = 1
    1.strftime = %Y
    1.wrap = &nbsp;|&nbsp;

    2 = TEXT
    2.value = {$site_default.website.settings.copyright}
    2.wrap = |
}
