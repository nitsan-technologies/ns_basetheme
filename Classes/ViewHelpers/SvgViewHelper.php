<?php
namespace NITSAN\site_default\ViewHelpers;


use TYPO3\CMS\Core\Utility\GeneralUtility;

class SvgViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * render inline svg
	 * @param string $svg path to svg
	 * @param array $attributes attributes to add
	 * @return string content
	 */
	public function render($svg, $attributes) {

		/* get SVG content */
		$svgContent = @file_get_contents(GeneralUtility::getFileAbsFileName($svg));

		/* if successfull render svg with attributes */
		if(is_string($svgContent) && trim($svgContent) && strpos($svgContent, '<svg') !== false) {

			/* remove id from svg to prevent double ids */
			if(strpos($svgContent, 'id=') !== false) {
				$svgContent = preg_replace('/(<[^>]+) id=".*?"/i', '$1', $svgContent);
			}

			/* set attributes */
			if(is_array($attributes) && sizeof($attributes)>0) {

				foreach ($attributes as $attributeName => $attributeValue) {

					/* check attribute is still present and remove */
					if(strpos($svgContent, $attributeName) !== false) {
						$svgContent = preg_replace('/(<[^>]+) '.$attributeName.'=".*?"/i', '$1', $svgContent);
					}

					/* build in the attribute */
					$svgContent = str_replace('<svg','<svg '.$attributeName.'="'.$attributeValue.'" ',$svgContent);
				}

			}

			$content = $svgContent;

		}
		/* else do a img tag */
		else {

			$content = '<img src="'.$svg.'"';

			/* set attributes */
			if(is_array($attributes) && sizeof($attributes)>0) {

				foreach ($attributes as $attributeName => $attributeValue) {

					/* build in the attribute */
					$content = $content.' '.$attributeName.'="'.$attributeValue.'"';
				}

			}

			/* check for mandatory alt tag and set if missing */
			if(!(is_array($attributes) && isset($attributes['alt']))) {
				$content .= ' alt=""';
			}

			$content .= ' />';

		}

		return $content;
	}
}