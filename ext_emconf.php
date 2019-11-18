<?php
/*
 * This file is part of the package nitsan/ns-basetheme.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

// TYPO3 Security Check
defined('TYPO3_MODE') or die();

// Provide detailed information and depenencies of EXT:ns_basetheme
$EM_CONF[$_EXTKEY] = [
    'title' => '[NITSAN] TYPO3 Parent/Base Theme',
    'description' => 'The TYPO3 theme which is design for parent and child theme concept, This parent theme have common and global configuration to support the child theme. Read more at documentation.',
    'category' => 'templates',
    'author' => 'Team NITSAN',
    'author_email' => 'sanjay@nitsan.in',
    'author_company' => 'NITSAN Technologies Pvt Ltd',
    'state' => 'stable',
    'version' => '4.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '8.0.0-10.99.99',
            'gridelements' => '8.0.0-10.99.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'classmap' => ['Classes/']
    ]
];
