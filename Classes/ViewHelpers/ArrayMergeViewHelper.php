<?php
namespace NITSAN\site_default\ViewHelpers;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2018 Enrico Kaspar <enrico.kaspar@ressourcenmangel.de>, Ressourcenmangel an der Panke GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


class ArrayMergeViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * initializes the arguments
	 *
	 */
	public function initializeArguments()
	{
		$this->registerArgument('array1','array','First Array to merge', TRUE);
		$this->registerArgument('array2','array','Second Array to merge', TRUE);
		$this->registerArgument('variableName','string','Variable Name to store', FALSE,'merged');
	}

	/**
	 * @return string content
	 */
	public function render() {

		$array1 = $this->arguments['array1'];
		$array2 = $this->arguments['array2'];
		$variableName = $this->arguments['variableName'];

		if (!is_array($array1) && !is_array($array2)) $value = null;
		elseif (!is_array($array1)) $value = $array2;
		elseif (!is_array($array2)) $value = $array1;
		else $value = array_merge($array1, $array2);

		$this->templateVariableContainer->add($variableName, $value);
		$content = $this->renderChildren();
		$this->templateVariableContainer->remove($variableName);

		return $content;
	}
}