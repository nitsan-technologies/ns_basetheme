<?php

return [
    'frontend' => [
        'nitsan/ns-basetheme' => [
            'target' => \NITSAN\NsBasetheme\Middleware\PwaMiddleware::class,
            'after' => [
                'typo3/cms-frontend/tsfe',
            ],
        ],
    ],
];