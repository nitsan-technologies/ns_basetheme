<?php
namespace NITSAN\NsBasetheme\Utility;
use \TYPO3\CMS\Core\Core\Environment;

/**
 * Class AnimationUtility
 */
class AnimationUtility {
    /**
     * @return array
     */
    public static function animationEffect($sourceFile)
    {
        $sourceFile = Environment::getPublicPath().'/'.$sourceFile;
        if(file_exists($sourceFile)){
            $animations_json = file_get_contents($sourceFile);
            $json = json_decode( $animations_json, true);
            
            $output = [];
            foreach ( $json['effects'] as $key => $value ) {
                $item = [$key,$value];
                array_push($output,$item);
            }
        }
        else{
            $output = [['no source file found under: '.Environment::getPublicPath(),'None']];
        }
        return $output;
    }

    /**
     * @return array
     */
    public static function animationEasing($sourceFile)
    {
        $sourceFile = Environment::getPublicPath().'/'.$sourceFile;
        if(file_exists($sourceFile)){
            $easings_json = file_get_contents($sourceFile);
            $json = json_decode( $easings_json, true);
            
            $easings = [];
            foreach ( $json['easings'] as $key => $value ) {
                $item = [$key,$value];
                array_push($easings,$item);
            }
        }
        else{
            $easings = [['no source file found under: '.Environment::getPublicPath(),'None']];
        }
        return $easings;
    }
}
