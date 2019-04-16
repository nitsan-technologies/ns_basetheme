# Let's define some constants for global configuration
site_default {	
	website {
		settings {
			#cat = site_default/website/settings/01; type=string; label=Logo Path
			logo = typo3conf/ext/site_default/Resources/Public/Images/Logo.png

			#cat = site_default/website/settings/02; type=string; label=Copyright Text
			copyright = Copyright
		}

		# Define paths for templates, layout and partial
		paths {
			templateRootPath = typo3conf/ext/site_default/Resources/Private/Templates/
			layoutRootPath = typo3conf/ext/site_default/Resources/Private/Layouts/
			partialRootPath = typo3conf/ext/site_default/Resources/Private/Partials/
		}
	}
}

# Define templates and partials paths for "Custom Components"
plugin.tx_nitsan_pi1 {
    view {
        templateRootPath = EXT:site_default/Resources/Private/Templates/Components/
        partialRootPath = EXT:site_default/Resources/Private/Partials/
    }
}