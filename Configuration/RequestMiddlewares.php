<?php

declare(strict_types=1);

return [
    'frontend' => [
        'nitsan/ns-basetheme' => [
            'target' => \NITSAN\NsBasetheme\Middleware\PwaMiddleware::class,
            'after' => [
                // v14: `typo3/cms-frontend/tsfe` no longer exists; site provides Site attribute used by PWA.
                'typo3/cms-frontend/site',
            ],
        ],
    ],
];
