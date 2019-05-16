<?php
// TYPO3 Security Check
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

// Add default include static TypoScript (for root page)
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript',
    'Default TYPO3 Theme & Templates');

// Get Components from ext_localconf.php
$allComponents = constant('ALL_COMPONENTS');

// Let's prepare CType components to add at TypoScript Config
$tsComponents = '';
foreach ($allComponents as $extKey => $extValue) {
    foreach ($extValue as $key => $theComponent) {

        $arrTemplateName = explode("_", $theComponent);
        $templateName = ucfirst($arrTemplateName[0]) . "" . ucfirst($arrTemplateName[1]);
        if (!empty($templateName)) {
            $tsComponents .= "
		        $theComponent = FLUIDTEMPLATE
		        $theComponent {
		            templateRootPaths {
		                0 = EXT:fluid_styled_content/Resources/Private/Templates/
		                10 = EXT:$extKey/Resources/Private/Components/
		            }
		            partialRootPaths {
		                0 = EXT:fluid_styled_content/Resources/Private/Partials/
		                10 = EXT:$extKey/Resources/Private/Partials/
		            }
		            variables {       
		            }
		            templateName = $templateName
		            dataProcessing {
		                10 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
		                10 {
		                    references.fieldName = image
		                    as = image
		                }            
		                20 = NITSAN\site_default\DataProcessing\DefaultProcessor
		            }
		        }
		    ";
        }
    }
}

// Add TypoScript for tt_content as setup.ts
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($_EXTKEY, 'setup', "
    tt_content {
        $tsComponents
    }
");