<?php
namespace NITSAN\NITSANbmelcontent\Hooks;
use Doctrine\DBAL\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Messaging\FlashMessage;


/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012-2013 Matthias Kappenberg <mail@the-dimension.com>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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

class PreProcessFields {

	/**
	 * The main function after saving a record
	 * use t3lib_utility_Debug::debug($fieldArray) for debugging...
	 *
	 * @param	array	$fieldArray: The record data to save
	 * @param	string	$table: Table to save to
	 * @param	integer	$id: The record id
	 * @param	object	$pObj: Reference to the object
	 * @return	void
	 */
	public function processDatamap_preProcessFieldArray(&$fieldArray, $table, $id, &$pObj) {

		if($table == 'tt_content' && $fieldArray['CType'] == 'bmel_publicationteaser') {

		}

	}

	/**
	 * @return string
	 */
	private function getRootPath() {

		if (trim($_SERVER["DOCUMENT_ROOT"])) {
			$rootpath = $_SERVER["DOCUMENT_ROOT"];
		} else {
			$rootpath = realpath(dirname(__FILE__) . '/../../../../../');
		}

		return rtrim($rootpath, '/');
	}

	/**
	 * @param string $title
	 * @param string $message
	 * @param int $type
	 */
	private function showWarning($title,$message,$type = FlashMessage::WARNING) {

		$message = GeneralUtility::makeInstance(
			FlashMessage::class,
			$message,
			$title,
			$type
		);
		$service = GeneralUtility::makeInstance(FlashMessageService::class);
		$queue = $service->getMessageQueueByIdentifier();
		$queue->addMessage($message);

	}

	/**
	 * @param $key
	 * @return mixed
	 */
	private function getTranslation($key) {
		return $GLOBALS['LANG']->sL('LLL:typo3conf/ext/site_default/Resources/Private/Language/locallang_db.xlf:'.$key);
	}
}