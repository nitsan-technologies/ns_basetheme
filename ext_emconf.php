<?php

// Provide detailed information and depenencies of EXT:ns_basetheme
$EM_CONF['ns_basetheme'] = [
    'title' => '[NITSAN] TYPO3 Base Template',
    'description' => 'The architecture of parent/child TYPO3 theme concept. Explore https://t3terminal.com and https://nitsantech.com',
    'category' => 'templates',
    'author' => 'Team NITSAN',
    'author_email' => 'sanjay@nitsan.in',
    'author_company' => 'NITSAN Technologies Pvt Ltd',
    'state' => 'stable',
    'version' => '11.5.6',
    'constraints' => [
        'depends' => [
            'typo3' => '8.0.0-11.5.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'classmap' => ['Classes/']
    ]
];
