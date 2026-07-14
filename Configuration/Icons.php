<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

$iconPath = 'EXT:ns_basetheme/Resources/Public/Icons/Container/';

return [
    'container-5col' => [
        'provider' => SvgIconProvider::class,
        'source' => $iconPath . 'container-5col.svg',
    ],
    'container-6col' => [
        'provider' => SvgIconProvider::class,
        'source' => $iconPath . 'container-6col.svg',
    ],
    'ns_base_5Cols' => [
        'provider' => SvgIconProvider::class,
        'source' => $iconPath . 'container-5col.svg',
    ],
    'ns_base_6Cols' => [
        'provider' => SvgIconProvider::class,
        'source' => $iconPath . 'container-6col.svg',
    ],
];
