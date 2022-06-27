<?php

namespace NITSAN\NsBasetheme\ExpressionLanguage;

use TYPO3\CMS\Core\ExpressionLanguage\AbstractProvider;

class CheckCookieConcent extends AbstractProvider
{
    public function __construct()
    {
        $checkValue = (isset($_COOKIE['cookieconsent_status'])) ? $_COOKIE['cookieconsent_status'] : null;
        $this->expressionLanguageVariables = [
            'cookieconsent_status' => $checkValue,
        ];
    }
}