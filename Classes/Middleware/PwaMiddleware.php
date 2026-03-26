<?php
declare(strict_types=1);

namespace NITSAN\NsBasetheme\Middleware;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Page\PageRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Resource\Exception\InvalidFileException;

class PwaMiddleware implements MiddlewareInterface
{
  protected ServerRequestInterface $request;

  const MANIFEST_NAME = 'site.webmanifest';

  /**
   * @throws InvalidFileException
   */
  public function process(
      ServerRequestInterface $request,
      RequestHandlerInterface $handler
  ): ResponseInterface {
      $this->request = $request;
      $this->processPwa();
      return $handler->handle($this->request);
  }

  /**
   * processPwa
   *
   * @return void
   */
  protected function processPwa(): void
  {
    $configurations = $this->getConfigurations();
    $this->addHeaderData($configurations);
    $data = $this->prepareJsonData($configurations);
    $this->processFiles($data);
  }

  /**
   * getConfigurations
   *
   * @return array
   */
  protected function getConfigurations(): array
  {
    $siteData = $this->request->getAttribute('site');
    return $siteData->getConfiguration();
  }

  /**
   * addHeaderData
   *
   * @param array $configurations
   * @return void
   */
    protected function addHeaderData(array $configurations): void
  {
    $siteUrl = $this->request->getAttribute('normalizedParams')->getSiteUrl();
    $manifestUrl = $siteUrl . self::MANIFEST_NAME . '?' . time();
    $configurationsName = $configurations['name'] ?? '';
    $configurationsIcon = $configurations['icon'] ?? '';

    $configurationsColor =   $configurations['theme_color'] ?? '';
    if (isset($configurations["enabled"]) && $configurations["enabled"] == true){
      $headerData = "<link rel='manifest' href='{$manifestUrl}'>";
      $headerData .= '<meta name="apple-mobile-web-app-capable" content="yes">';
      $headerData .= '<meta name="apple-mobile-web-app-status-bar-style" content="black">';
      $headerData .= "<meta name='apple-mobile-web-app-title' content='{$configurationsName}'>";
      $headerData .= "<link rel='apple-touch-icon' href='{$configurationsIcon}'>";
      $headerData .= "<meta name='msapplication-TileImage' content='{$configurationsIcon}'>";
      $headerData .= "<meta name='theme-color' content='{$configurationsColor}'>";
      $headerData .= "<meta name='msapplication-TileColor' content='{$configurationsColor}'>";
      GeneralUtility::makeInstance(PageRenderer::class)->addHeaderData($headerData);
    }
  }

  /**
   * prepareJsonData
   *
   * @param array $configurations
   * @return array
   */
  protected function prepareJsonData(array $configurations): array
  {
    $configurationsEnabled  = $configurations['enabled'] ?? '';
    $configurationsShortName  = $configurations['short_name'] ?? '';
    $configurationsName  = $configurations['name'] ?? '';
    $configurationsIcon_192  = $configurations['icon_192'] ?? '';
    $configurationsIcon_192_type = $configurations['icon_192_type'] ?? '';
    $configurationsIcon_512 = $configurations['icon_512'] ?? '';
    $configurationsIcon_512_type = $configurations['icon_512_type'] ?? '';
    $configurationsIcon_144 =  $configurations['icon_144'] ?? '';
    $configurationsIcon_144_type = $configurations['icon_144_type'] ?? '';
    $configurationsStart_URL = $configurations['start_url'] ?? '';
    $configurationsBackground_color = $configurations['background_color'] ?? '';
    $configurationsDispaly = $configurations['display'] ?? '';
    $configurationsScope = $configurations['scope'] ?? '';
    $configurationsThemeColor = $configurations['theme_color'] ?? '';

    $data = [
        "short_name" => "$configurationsShortName",
        "name" => "$configurationsName",
        "icons" => [
            [
              "src" => "$configurationsIcon_192",
              "sizes" => "192x192",
              "type" =>  "$configurationsIcon_192_type",
              "density" => 4
            ],
            [
              "src" => "$configurationsIcon_512",
              "sizes" => "512x512",
              "type" => "$configurationsIcon_512_type"
            ],
            [
              "src" => "$configurationsIcon_144",
              "sizes" => "144x144",
              "type" => "$configurationsIcon_144_type",
              "purpose" => "maskable"
            ]
        ],
        "start_url" =>  "$configurationsStart_URL",
        "background_color" => "$configurationsBackground_color",
        "display" => "$configurationsDispaly",
        "scope" => "$configurationsScope",
        "theme_color" => "$configurationsThemeColor",
    ];

    // Check if ss_icon_mobile exists and add it to the screenshots array
    if (!empty($configurations["ss_icon_desktop"]))
    {
        $data["screenshots"][] = [
            "src" => "$configurations[ss_icon_desktop]",
            "sizes" => "$configurations[ss_icon_size_desktop]",
            "type" => "$configurations[ss_icon_desktop_type]",
            "form_factor" => "wide",
            "label" => "For Desktop"
        ];
    }
    if (!empty($configurations["ss_icon_mobile"]))
    {
        $data["screenshots"][] = [
            "src" => "$configurations[ss_icon_mobile]",
            "sizes" => "$configurations[ss_icon_size_mobile]",
            "type" => "$configurations[ss_icon_mobile_type]",
            "form_factor" => "narrow",
            "label" => "For Mobile"
        ];
    }

    return $data;
  }

  /**
   * processFiles
   *
   * @param array $data
   * @return void
   */
  protected function processFiles(array $data): void
  {
    $versionInformation = GeneralUtility::makeInstance(Typo3Version::class);
    $versionInformation->getMajorVersion();
    $pwaFileadminPath = '/fileadmin/pwa';

    if (Environment::isComposerMode())
    {
      //Creating PWA Directory
      if(!is_dir(Environment::getPublicPath() .$pwaFileadminPath)){
        GeneralUtility::mkdir(Environment::getPublicPath() .$pwaFileadminPath);
      }
      // Copy PWA icons from extension to fileadmin
      if($versionInformation->getMajorVersion() >= 12){
        $this->copyfolder(Environment::getProjectPath() . "/vendor/nitsan/ns-basetheme/Resources/Public/pwa/icons/", Environment::getPublicPath() . '/' . $pwaFileadminPath . '/');
      }
      else{
        $this->copyfolder(Environment::getPublicPath() . "/typo3conf/ext/ns_basetheme/Resources/Public/pwa/icons/", Environment::getPublicPath() . '/' . $pwaFileadminPath . '/');
      }
      //Creating JavaScript file and append data
      $jsonFile = Environment::getPublicPath().'/'.self::MANIFEST_NAME;
      if (!file_exists($jsonFile)) {
        fopen(Environment::getPublicPath(). "/".self::MANIFEST_NAME, "w") or die("Unable to open file!");
      }
      GeneralUtility::writeFile($jsonFile, json_encode($data));
    }
    else{
      //File Creation and clone icons folder from extension
      if($versionInformation->getMajorVersion() >= 12)
      {
        //Creating PWA Directory
        if(!is_dir(Environment::getPublicPath() .$pwaFileadminPath)){
          mkdir(Environment::getPublicPath() .$pwaFileadminPath);
        }
        $this->copyfolder(Environment::getPublicPath() . "/typo3conf/ext/ns_basetheme/Resources/Public/pwa/icons/", Environment::getProjectPath() . '/' . 'fileadmin/pwa/');

        //Creating JavaScript file and append data
        $jsonFile = Environment::getPublicPath().'/'.self::MANIFEST_NAME;
        if (!file_exists($jsonFile)) {
          fopen(Environment::getPublicPath(). "/".self::MANIFEST_NAME, "w") or die("Unable to open file!");
        }
        GeneralUtility::writeFile($jsonFile, json_encode($data));
      }
      else{
        //Creating PWA Directory
        if(!is_dir(Environment::getPublicPath() .$pwaFileadminPath)){
          mkdir(Environment::getPublicPath() .$pwaFileadminPath);
        }
        $this->copyfolder(Environment::getPublicPath() . "/typo3conf/ext/ns_basetheme/Resources/Public/pwa/icons/", Environment::getPublicPath() . '/' . 'fileadmin/pwa/');

        $jsonFile = Environment::getPublicPath().'/'.self::MANIFEST_NAME;
        if (!file_exists($jsonFile)) {
          fopen(Environment::getPublicPath(). "/".self::MANIFEST_NAME, "w") or die("Unable to open file!");
        }
        GeneralUtility::writeFile($jsonFile, json_encode($data));
      }
    }
  }

  /**
   * copyfolder
   *
   * @param string $from
   * @param string $to
   * @param string $ext
   * @return void
   */
  protected function copyfolder(string $from, string $to, string $ext="*"): void
  {
    // Source Folder Check
    if (!is_dir($from)) { exit("$from does not exist"); }

    // Create Destination Folder
    if (!is_dir($to)) {
      if (!mkdir($to)) { exit("Failed to create $to"); };
      echo "$to created\r\n";
    }

    // Get all files + folders in source
    $all = glob("$from$ext", GLOB_MARK);

    // Copy files + recursive internal folders
    if (count($all)>0)
    {
      foreach ($all as $a)
      {
        $ff = basename($a); // Current file/folder
        if (is_dir($a))
        {
          $this->copyfolder("$from$ff/", "$to$ff/");
        }
        else {
          if (!copy($a, "$to$ff"))
          {
            exit("Error copying $a to $to$ff");
          }
        }
      }
    }
  }

}
