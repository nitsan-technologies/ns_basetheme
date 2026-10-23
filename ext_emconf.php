<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'TYPO3 Base Template',
    'description' => 'The architecture of parent/child TYPO3 theme concept. Explore https://t3planet.com and https://nitsantech.com',
    'category' => 'templates',
    'version' => '14.0.6',
    'state' => 'stable',
    'clearCacheOnLoad' => 0,
    'author' => 'Team T3Planet',
    'author_email' => 'info@t3planet.de',
    'author_company' => 'T3Planet',
    'constraints' => [
        'depends' => [
            'typo3' => '14.0.0-14.9.99',
            'ns_license' => '14.5.0-14.9.99',
            'php' => '8.2.0-8.5.99',
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
