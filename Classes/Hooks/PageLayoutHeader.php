<?php

declare(strict_types=1);

namespace NITSAN\NsBasetheme\Hooks;

/**
 * Legacy Page module hook. Asset loading moved to BackendCssLayout.
 */
class PageLayoutHeader
{
    public function render(?array $params = null, mixed $parentObj = null): string
    {
        return '';
    }
}
