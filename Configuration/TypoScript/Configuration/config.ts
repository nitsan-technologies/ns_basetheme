config{
	baseURL >
	absRefPrefix >
	doctype = html5
	xmlprologue = none
	linkVars = L
	uniqueLinkVars = 0
	admPanel = 0
	extTarget = _blank
	fileTarget = _blank

	
	# Mail Spamming Security
	spamProtectEmailAddresses = ascii

	# For Caching
	cache_period = 86400
	no_cache = 0

	# For Debugging
	contentObjectExceptionHandler = {$site_default.website.debugging.contentObjectExceptionHandler}
	
	# For RealURL
	tx_realurl_enable = 1

	# For enable indexed search
	index_enable = 1

	# index files
	index_externals = 1
	# don't index metatags
	index_metatags = 0

	# Site Optimization
	moveJsFromHeaderToFooter = 1
	compressJs = {$site_default.website.optimization.compressJs}
	compressCss = {$site_default.website.optimization.compressCss}
	concatenateJs = {$site_default.website.optimization.concatenateJs}
	concatenateCss = {$site_default.website.optimization.concatenateCss}
}

config.baseURL = http://{$site_default.website.settings.baseURL}
config.absRefPrefix = {$site_default.website.settings.absRefPrefix}
[globalString = _SERVER|HTTPS=on]
    config.baseURL = https://{$site_default.website.settings.baseURL}
[global]

[globalVar = GP:L = 1]
config {
	htmlTag_langKey = en
	sys_language_uid = 1
	language = en
	locale_all = en_EN
}
[global]

