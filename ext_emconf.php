<?php

// Provide detailed information and depenencies of EXT:ns_basetheme
$EM_CONF['ns_basetheme'] = [
    'title' => '[NITSAN] TYPO3 Base Template',
    'description' => 'The architecture of parent/child TYPO3 theme concept. Explore https://t3planet.com and https://nitsantech.com',
    'category' => 'templates',
    'author' => 'Team NITSAN',
    'author_email' => 'sanjay@nitsan.in',
    'author_company' => 'NITSAN Technologies Pvt Ltd',
    'state' => 'stable',
    'version' => '12.0.4',
    'constraints' => [
        'depends' => [
            'typo3' => '12.0.0-12.4.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'classmap' => ['Classes/']
    ]
];
