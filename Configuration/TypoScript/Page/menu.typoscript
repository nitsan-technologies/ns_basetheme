// Draw main menu
menu {
    main = HMENU
    main {

        special = directory
        special.value = {$ns_basetheme.website.settings.main_menu}

        1 = TMENU
        1 {
            wrap = <ul class="navbar-nav ml-auto">|</ul>
            expAll = 1
            noBlur = 1

            NO = 1
            NO {
                ATagTitle {
                    field = title
                    fieldRequired = nav_title
                }

                ATagParams = class="nav-link parent_menu"

                ATagBeforeWrap = 1
                linkWrap = |

                wrapItemAndSub.insertData = 1
                wrapItemAndSub = <li class="nav-item first menu-{field:uid}">|</li> |*| <li class="menu-{field:uid}">|</li>
                stdWrap.htmlSpecialChars = 1
            }

            IFSUB < .NO
            IFSUB {
                wrapItemAndSub.insertData = 1
                wrapItemAndSub = <li class="menu-{field:uid}">|</li>
            }

            ACT < .NO
            ACT {
                ATagParams = class="nav-link parent_menu active"
                wrapItemAndSub = <li class="active first menu-{field:uid}">|</li> |*| <li class="active menu-{field:uid}">|</li>
            }

            CUR < .NO
            CUR {
                ATagParams = class="nav-link parent_menu active"
                wrapItemAndSub = <li class="first active menu-{field:uid}">|</li> |*| <li class="active menu-{field:uid}">|</li>
            }
        }

        2 < .1
        2 {
            wrap = <ul>|</ul>
            NO.ATagParams >
            ACT.ATagParams = class="active"
            CUR.ATagParams = class="active"
            NO.wrapItemAndSub = <li>|</li>
        }

        3 < .2
        // 3.NO.doNotLinkIt = 1 |*| 0 |*| 0
    }

    footer = HMENU
    footer {

        special = directory
        special.value = {$ns_basetheme.website.settings.footer_menu}

        1 = TMENU
        1 {
            wrap = |
            expAll = 1
            noBlur = 1

            NO = 1
            NO {
                ATagTitle {
                    field = title
                    fieldRequired = nav_title
                }

                ATagParams = class="text-white"

                ATagBeforeWrap = 1
                linkWrap = | &nbsp;
            }
        }
    }
}

// Breadcrumb Menu
lib.breadcrumb = COA
lib.breadcrumb {
    10 = HMENU
    10 {
        special = rootline
        // special.range = 0|-1
        // "not in menu pages" should show up in the breadcrumbs menu
        includeNotInMenu = 1

        1 = TMENU
        1 {
            noBlur = 1
            # Current item should be unlinked
            target = _self
            wrap = <ol class="breadcrumb"> | </ol>
            NO {
                stdWrap.field = title
                ATagTitle.field = nav_title // title
                linkWrap = <li class="breadcrumb-item">|</li>
            }

            # Current menu item is unlinked
            CUR = 1
            CUR < .NO
        }
    }
}