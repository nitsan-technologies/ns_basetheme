<?php

// Provide detailed information and depenencies of EXT:ns_basetheme
$EM_CONF['ns_basetheme'] = [
    'title' => '[NITSAN] Base Template',
    'description' => 'The TYPO3 template which is design for parent and child theme concept, This parent theme have common and global configuration to support the child theme. Read more at documentation. Read more at https://t3terminal.com/blog/typo3-templates-builder/',
    'category' => 'templates',
    'author' => 'Team NITSAN',
    'author_email' => 'sanjay@nitsan.in',
    'author_company' => 'NITSAN Technologies Pvt Ltd',
    'state' => 'stable',
    'version' => '10.4.3',
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
