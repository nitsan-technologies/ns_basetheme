<?php
// TYPO3 Security Check
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

// Provide detailed information and depenencies of EXT:site_default
$EM_CONF[$_EXTKEY] = array(
	'title' => 'Default TYPO3 Theme & Templates',
	'description' => 'Purpose of the extension is to use as a default theme for the site, To manage backend/frontend layouts, templates, site global configuration, custom content elements and many more.',
	'category' => 'fe',
	'author' => '-',
	'author_email' => '-',
	'author_company' => '-',
	'state' => 'stable',
	'version' => '2.0.1',
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