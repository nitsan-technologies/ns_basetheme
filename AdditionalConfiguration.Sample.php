<?php
return [
   'BE' => [
      'debug' => true,
      'explicitADmode' => 'explicitAllow',
      'installToolPassword' => '',
      'loginSecurityLevel' => 'rsa',
   ],
   'DB' => [
      'Connections' => [
         'Default' => [
            'charset' => 'utf8',
            'dbname' => '',
            'driver' => 'mysqli',
            'host' => '127.0.0.1',
            'password' => '',
            'port' => 3306,
            'user' => '',
         ],
      ],
   ],
   'EXTCONF' => [
       'lang' => [
           'availableLanguages' => [
               'de',
           ],
       ],
   ],
   'EXTENSIONS' => [
       'backend' => [
           'backendFavicon' => '',
           'backendLogo' => '',
           'loginBackgroundImage' => '',
           'loginFootnote' => '',
           'loginHighlightColor' => '',
           'loginLogo' => '',
       ],
       'extensionmanager' => [
           'automaticInstallation' => '1',
           'offlineMode' => '0',
       ],
       'scheduler' => [
           'maxLifetime' => '1440',
           'showSampleTasks' => '1',
       ],
   ],
   'FE' => [
      'debug' => '',
      'loginSecurityLevel' => 'rsa',
      'pageNotFoundOnCHashError' => false,
      'pageNotFound_handling' => 'REDIRECT:',
   ],
   'GFX' => [
      'jpg_quality' => '80',
   ],
   'MAIL' => [
      'transport_sendmail_command' => '/usr/sbin/sendmail -t -i ',
   ],
   'SYS' => [
      'caching' => [
          'cacheConfigurations' => [
              'extbase_object' => [
                  'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
                  'frontend' => 'TYPO3\\CMS\\Core\\Cache\\Frontend\\VariableFrontend',
                  'groups' => [
                      'system',
                  ],
                  'options' => [
                      'defaultLifetime' => 0,
                  ],
              ],
          ],
      ],
      'clearCacheSystem' => FALSE,
      'devIPmask' => '',
      'displayErrors' => '1',
      'enableDeprecationLog' => FALSE,
      'exceptionalErrors' => 20480,
      'isInitialInstallationInProgress' => FALSE,
      'isInitialDatabaseImportDone' => true,
      'isInitialInstallationInProgress' => false,
      'sitename' => 'Site Name',
      'sqlDebug' => '1',
      'systemLogLevel' => '2',
   ],
];