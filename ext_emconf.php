<?php
/*
 * This file is part of the package nitsan/site-default.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

// TYPO3 Security Check
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

// Provide detailed information and depenencies of EXT:site_default
$EM_CONF[$_EXTKEY] = array(
	'title' => 'Default TYPO3 Theme & Templates',
	'description' => 'Site-default delivers a full configured frontend theme for TYPO3, based on the Bootstrap CSS Framework.',
	'category' => 'templates',
	'author' => '-',
	'author_email' => '-',
	'author_company' => '-',
	'state' => 'stable',
	'version' => '2.0.2',
	'constraints' => array(
		'depends' => array(
			'typo3' => '8.0.0-9.5.99',
			'news' => '4.3.0-7.2.99',
			'gridelements' => '8.0.0-9.2.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'autoload' => array(
		'classmap' => array('Classes/')
	)
);
?>