# Let's define some constants for global configuration
site_default {	
	website {
		settings {
			#cat = site_default/website/settings/01; type=string; label=Logo Path
			logo = typo3conf/ext/site_default/Resources/Public/Images/Logo.png

			#cat = site_default/website/settings/02; type=boolean; label=Cache enable/disable
			no_cache = 1

			#cat = site_default/website/settings/03; type=string; label=Copyright Text
			copyright = Copyright

			#cat = site_default/website/settings/04; type=int; label=Main Menu ID
			main_menu = 12

			#cat = site_default/website/settings/05; type=int; label=Footer Menu ID
			footer_menu = 7
			
			#cat = site_default/website/settings/06; type=string; label=Root Page Id
			rootpage = 1

			#cat = site_default/website/settings/07; type=boolean; label=Compress and Concatenate CSS/JS
			compress_cssjs = 0
		}

		# Define paths for templates, layout and partial
		paths {
			templateRootPath = typo3conf/ext/site_default/Resources/Private/Templates/
			layoutRootPath = typo3conf/ext/site_default/Resources/Private/Layouts/
			partialRootPath = typo3conf/ext/site_default/Resources/Private/Partials/
		}
	}
}