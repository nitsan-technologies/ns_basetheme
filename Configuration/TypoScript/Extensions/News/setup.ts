# Let's overwrite default paths of EXT:news
plugin.tx_news {
    view {
        templateRootPaths {
            0 = EXT:site_default/Resources/Private/Extensions/news/Templates/
            1 = {$plugin.tx_news.view.templateRootPath}
        }

        partialRootPaths {
            0 = EXT:site_default/Resources/Private/Extensions/news/Partials/
            1 = {$plugin.tx_news.view.partialRootPath}
        }

        layoutRootPaths {
            0 = EXT:site_default/Resources/Private/Extensions/news/Layouts/
            1 = {$plugin.tx_news.view.layoutRootPath}
        }
    }
}

# Used when need to extend news plugin
# plugin.tx_news {
#     persistence {
#         classes {
#             GeorgRinger\News\Domain\Model\News {
#                     subclasses {
#                             0 = TYPO3\SiteDefault\Domain\Model\News
#                     }
#             }
#            TYPO3\SiteDefault\Domain\Model\News {
#                     mapping {
#                             recordType = 0
#                             tableName = tx_news_domain_model_news
#                     }
#             }
#         }
#     }
# }