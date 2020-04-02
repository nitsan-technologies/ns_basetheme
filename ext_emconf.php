<?php

// Provide detailed information and depenencies of EXT:ns_basetheme
$EM_CONF[$_EXTKEY] = [
    'title' => '[NITSAN] Base Theme/Template',
    'description' => 'The TYPO3 theme which is design for parent and child theme concept, This parent theme have common and global configuration to support the child theme. Read more at documentation.',
    'category' => 'templates',
    'author' => 'Team NITSAN',
    'author_email' => 'sanjay@nitsan.in',
    'author_company' => 'NITSAN Technologies Pvt Ltd',
    'state' => 'stable',
    'version' => '10.1.0',
    'constraints' => [
        'depends' => [
            'typo3' => '8.0.0-10.9.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'classmap' => ['Classes/']
    ]
];
