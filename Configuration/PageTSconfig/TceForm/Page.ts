# Change backend layout config
TCEFORM.pages.backend_layout_next_level.removeItems = -1,
TCEFORM.pages.backend_layout.removeItems = -1,

# Let's disable some configuration of Copy/Paste
TCEMAIN {
    table {
        tt_content {
           disablePrependAtCopy = 1
           disableHideAtCopy = 1
        }
        pages {
           disablePrependAtCopy = 1
           disableHideAtCopy = 0
        }
    }
}

# Let's make responsive design with Desktop, Tablet and Mobile
TCEFORM.sys_file_reference.crop.config.cropVariants {
    default {
        title = LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:imageManipulation.desktop
        selectedRatio = NaN
        allowedAspectRatios {
            NaN {
                title = LLL:EXT:lang/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.free
                value = 0.0
            }
        }
    }
    specialTablet {
        title = LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:imageManipulation.tablet
        selectedRatio = NaN
        allowedAspectRatios {
            3:2 {
                title = LLL:EXT:lang/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.3_2
                value = 1.5
            }
        }
    }
    specialMobile {
        title = LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:imageManipulation.mobile
        selectedRatio = NaN
        allowedAspectRatios {
            4:3 {
                title = LLL:EXT:lang/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.4_3
                value = 1.3333333
            }
        }
    }
}

# TCEFORM.tt_content {

#      header_layout {
#         altLabels {
#              1 = h1
#              2 = h2
#              3 = h3
#              4 = h4
#              5 = h5
#         }
#     }  
#     layout {
#         types {
#             textmedia{
#                 altLabels.1 = 
#                 removeItems = 2,3
#             }

#         }
#     }    
# }

# TCEFORM.pages {
#     layout {        
    
#         config {
#             default = 0
#         }
#         altLabels.0 = 
#         altLabels.1 = 
#         removeItems = 2,3
#     }
#     backend_layout {
#         config {
#             default = 
#         }
#         removeItems = -1 
#     }
# }
# TCEFORM.tx_powermail_domain_model_form {
#         css {
#             altLabels.layout1 = 
#             altLabels.layout2 = 
#             altLabels.layout3 = 
#             addItems{
#                 layout4 = 
#                 layout5 = 
#             }
#             removeItems = nolabel
#         }
#     }
# }

# TCEFORM.tx_powermail_domain_model_page {
#         css {
#             altLabels.layout1 = 
#             altLabels.layout2 = 
#             removeItems = layout3,nolabel
#         }
#     }
# }

# TCEFORM.tx_powermail_domain_model_field {
#         css {
#             altLabels.layout1 = 
#             removeItems = layout3, layout2,nolabel
#         }
#     }
# }

# tx_news.templateLayouts {
#     1 = 
#     2 = 
# }