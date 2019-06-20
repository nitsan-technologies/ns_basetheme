# TypoScript for rendering in frontend
tt_content.gridelements_pi1.20.10.setup {

    # Two column grid container
    1 < lib.gridelements.defaultGridSetup
    1 {
        cObject = FLUIDTEMPLATE
        cObject {
            file = typo3conf/ext/site_default/Resources/Private/Extensions/Grid/twocol.html
        }
    }

    # Three column grid container
    2 < lib.gridelements.defaultGridSetup
    2 {
        cObject = FLUIDTEMPLATE
        cObject {
            file = typo3conf/ext/site_default/Resources/Private/Extensions/Grid/threeColumns.html
        }
    }

    # Four column grid container
    3 < lib.gridelements.defaultGridSetup
    3 {
        cObject = FLUIDTEMPLATE
        cObject {
            file = typo3conf/ext/site_default/Resources/Private/Extensions/Grid/fourcolumn.html
        }
    }
}