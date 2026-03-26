<?php

// Provide detailed information and depenencies of EXT:ns_basetheme
$EM_CONF['ns_basetheme'] = [
    'title' => 'TYPO3 Base Template',
    'description' => 'The architecture of parent/child TYPO3 theme concept. Explore https://t3planet.com and https://nitsantech.com',
    'category' => 'templates',
    'author' => 'Team NITSAN',
    'author_email' => 'sanjay@nitsan.in',
    'author_company' => 'NITSAN Technologies Pvt Ltd',
    'state' => 'stable',
    'version' => '14.0.0',
    'constraints' => [
        'depends' => [
             'typo3' => '14.0.0-14.9.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => [
            'NITSAN\\NsBasetheme\\' => 'Classes/',
        ],
    ],
];
