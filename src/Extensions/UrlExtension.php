<?php declare(strict_types=1);

/**
 * Twig View
 * @license https://opensource.org/licenses/MIT MIT
 * @author Renan Cavalieri <renan@tecdicas.com>
 */

namespace Pollus\TwigView\Extensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UrlExtension extends AbstractExtension
{
    /**
     * @return array
     */
    public function getFunctions() : array
    {
        return [new TwigFunction('url', [$this, 'url'], ['is_safe' => ['html']])];
    }
    
    /**
     * @param string $url
     * @param array $query
     * @return string
     */
    public function url(string $url = "", ?array $query = null)
    {
        if (!(strpos($url, "://") !== false))
            $url = htmlentities(rtrim(dirname($_SERVER["PHP_SELF"] ?? ""), "/") 
                    . "/" . ltrim($url, "/"), ENT_NOQUOTES, 'utf-8');
        
        if ($query !== null)
            $url .= "?" . http_build_query($query);
        
        return $url;
    } 
}
