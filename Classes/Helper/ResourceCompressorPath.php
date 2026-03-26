<?php
declare(strict_types=1);

namespace NITSAN\NsBasetheme\Helper;

/*  | This extension is made with ❤ for TYPO3 CMS and is licensed
 *  | under GNU General Public License.
 *  |
 *  | (c) 2017-2024 Armin Vieweg <armin@v.ieweg.de>
 */
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Minifier for JS and CSS
 * 
 * Note: ResourceCompressor was removed in TYPO3 14, so this class now implements
 * the functionality directly.
 *
 * @package T3\Min
 */
class ResourceCompressorPath
{
    protected string $targetDirectory;

    /**
     * ResourceCompressorPath constructor
     */
    public function __construct()
    {
        $this->targetDirectory = Environment::getPublicPath() . '/typo3temp/assets/';
    }

    /**
     * Fix relative URL paths in CSS code
     * 
     * This method replaces relative URLs in CSS with absolute paths
     * based on the source file location.
     * 
     * @param string $code The CSS code
     * @param string $filename The source filename (relative to public path)
     * @return string The CSS code with fixed URLs
     */
    public function fixRelativeUrlPathsInCssCode(string $code, string $filename): string
    {
        // Get the directory of the source file relative to public path
        $sourceDir = dirname($filename);
        if ($sourceDir === '.') {
            $sourceDir = '';
        } else {
            $sourceDir = rtrim($sourceDir, '/') . '/';
        }

        // Pattern to match url() declarations in CSS
        // Matches: url('path'), url("path"), url(path), url( path )
        $pattern = '/url\s*\(\s*(["\']?)([^"\'()]+)\1\s*\)/i';
        
        return preg_replace_callback($pattern, function ($matches) use ($sourceDir) {
            $url = $matches[2];
            $quote = $matches[1];
            
            // Skip if URL is already absolute (starts with http://, https://, //, or /)
            if (preg_match('/^(https?:|\/\/|\/)/i', $url)) {
                return $matches[0];
            }
            
            // Skip data URIs
            if (str_starts_with($url, 'data:')) {
                return $matches[0];
            }
            
            // Build the absolute path
            // Remove leading ./ if present
            $url = ltrim($url, './');
            
            // Combine source directory with the URL
            $absoluteUrl = '/' . $sourceDir . $url;
            
            // Normalize path (remove ../ and ./)
            $absoluteUrl = $this->normalizePath($absoluteUrl);
            
            return 'url(' . $quote . $absoluteUrl . $quote . ')';
        }, $code);
    }

    /**
     * Normalize a file path by removing . and .. segments
     * 
     * @param string $path The path to normalize
     * @return string The normalized path
     */
    protected function normalizePath(string $path): string
    {
        $parts = explode('/', $path);
        $stack = [];
        
        foreach ($parts as $part) {
            if ($part === '' || $part === '.') {
                continue;
            }
            if ($part === '..') {
                if (!empty($stack)) {
                    array_pop($stack);
                }
            } else {
                $stack[] = $part;
            }
        }
        
        return '/' . implode('/', $stack);
    }

    public function __toString(): string
    {
        return $this->targetDirectory;
    }
}
