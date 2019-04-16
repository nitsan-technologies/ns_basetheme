<?php
namespace NITSAN\site_default\ViewHelpers;

use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility as debug;

class LinklistViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

/**
* initializes the arguments
*
*/
public function initializeArguments()
{
   $this->registerArgument('link','string','The link to resolve the link type', TRUE);
}

	/**
	 * @param string $link
	 */
	public function render() {
        $link = $this->arguments['link'];
		if (strpos($link, 't3://') === 0) {
		    $urnParsed = parse_url($link);
            $type = $urnParsed['host'];
            if($type == 'file'){
                $uid = explode('=', $urnParsed['query']);
                $resourceFactory = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance();
                $file = $resourceFactory->getFileObject($uid[1]);
                $filedata = $file->getProperties();
                //debug::var_dump($filedata);
                return ['filetype'=>'file','size'=>$filedata['size'],'ext'=>$filedata['extension']];
                
            }else {
            	return 'page';
            }
            
		}
		else {
            return 'external';
        }

	}
}

