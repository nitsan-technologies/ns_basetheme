config {

	# Global common configuration
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
	no_cache = 1

	# For Debugging
	contentObjectExceptionHandler = 0
	
	# For TYPO3 v9 EXT:realurl
	// tx_realurl_enable = 1

	# For enable indexed search
	index_enable = 1

	# index files
	index_externals = 1
	
	# don't index metatags
	index_metatags = 0

	# Site Optimization
	moveJsFromHeaderToFooter = 1
	compressJs = 1
	compressCss = 1
	concatenateJs = 1
	concatenateCss = 1
}

# for TYPO3 v8, Mulit-Langauge configuration
/*
[globalVar = GP:L = 1]
config {
	htmlTag_langKey = en
	sys_language_uid = 1
	language = en
	locale_all = en_EN
}
[global]
*/