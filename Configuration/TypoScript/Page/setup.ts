
plugin.tx_sitedefault.settings.logo = {$site_default.website.settings.logo}
plugin.tx_sitedefault.settings.rootpage = {$site_default.website.settings.rootpage}
plugin.tx_sitedefault.settings.searchPage = {$site_default.website.settings.searchpage}

page = PAGE
page{
	typeNum = 0

	bodyTag >
	bodyTagCObject = TEXT
	bodyTagCObject.value = <body class='' id='page_{page:uid}'>
	bodyTagCObject.insertData = 1

	favicon = site_default/Resources/Public/images/favicons/favicon.ico

	headerData{
		10 = TEXT
		10.field = title
		10.wrap = <title>|</title>
	}

	meta{
		viewport = width=device-width,initial-scale=1, maximum-scale=1, user-scalable=no
		keywords.field = keywords
		description.field = description
		abstract.field = abstract
	}

	includeCSS{
		10 = typo3conf/ext/site_default/Resources/Public/Bootstrap/css/bootstrap.min.css
	}

	includeJSFooter{
		jquery = typo3conf/ext/site_default/Resources/Public/js/jquery-2.2.4.min.js
		bootstrap = typo3conf/ext/site_default/Resources/Public/Bootstrap/js/bootstrap.min.js
		custom = typo3conf/ext/site_default/Resources/Public/js/custom.js
	}

	footerData{

	}

	10 = FLUIDTEMPLATE
	10 {
		layoutRootPath = {$site_default.website.paths.layoutRootPath}
		partialRootPath = {$site_default.website.paths.partialRootPath}
		templateRootPath = {$site_default.website.paths.templateRootPath}

		file = {$site_default.website.paths.templateRootPath}Main.html

		settings < plugin.tx_sitedefault.settings
	}
}


lib.content1 = CONTENT
lib.content1 {
	table = tt_content
	select.where = colPos = 1
}
lib.copyright = COA
lib.copyright{
    stdWrap.wrap = <li>@|</li>

    1 = TEXT
    1.current = 1
    1.strftime = %Y
    1.wrap = &nbsp;|&nbsp;

    2 = TEXT
    2.value = {$site_default.website.settings.copyright}
    2.wrap = |
}

menu.main_menu = HMENU
menu.main_menu {
	
	#special = directory
	#special.value = 3
		
	1 = TMENU
	1 {
		wrap = <ul class="nav navbar-nav">|</ul>
		expAll = 1
		noBlur = 1
		
		NO = 1
		NO {
			ATagTitle {
				field = title
				fieldRequired = nav_title
			}
			ATagParams = class="parent_menu"
			
			ATagBeforeWrap = 1
			linkWrap = |
			
			wrapItemAndSub.insertData = 1
			wrapItemAndSub = <li class="first menu-{field:uid}">|</li> |*| <li class="menu-{field:uid}">|</li>
			stdWrap.htmlSpecialChars = 1			
		}
		
		IFSUB < .NO
		IFSUB {
			wrapItemAndSub.insertData = 1
			wrapItemAndSub = <li class="menu-{field:uid}">|</li>
		}
	
		ACT < .NO
		ACT {
			ATagParams = class="parent_menu active"
			wrapItemAndSub = <li class="active first menu-{field:uid}">|</li> |*| <li class="active menu-{field:uid}">|</li>
		}

		CUR < .NO
		CUR {
			ATagParams = class="parent_menu active"
			wrapItemAndSub = <li class="first active menu-{field:uid}">|</li> |*| <li class="active menu-{field:uid}">|</li>
		}
	}
	
	2 < .1
	2.wrap = <ul>|</ul>
	2.NO.ATagParams >
	2.ACT.ATagParams = class="active"
	2.CUR.ATagParams = class="active"
	2.NO.wrapItemAndSub = <li>|</li>
		
	3 < .2	
	#3.NO.doNotLinkIt = 1 |*| 0 |*| 0
}
