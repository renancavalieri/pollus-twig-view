<?php

/**
 * Twig View
 * @license https://opensource.org/licenses/MIT MIT
 * @author Renan Cavalieri <renan@tecdicas.com>
 */

namespace Pollus\TwigView;

use Psr\Http\Message\ResponseInterface;
use Pollus\ViewInterface\ViewInterface;
use Pollus\ViewInterface\BaseView;
use Twig\Environment;
use Pollus\ViewInterface\Exceptions\NullResponseException;

class TwigView extends BaseView implements ViewInterface
{
    /**
     * @var Environment
     */
    protected $twig;
    
    /**
     * @param Environment $twig 
     * @param array $vars 
     */
    public function __construct(Environment $twig, array $vars = array()) 
    {
        $this->twig = $twig;
        $this->vars = $vars;
    }
    
    /**
     * {@inheritDoc}
     */
    public function render(string $template, array $vars = array()): ResponseInterface 
    {
        if ($this->response === null)
            throw new NullResponseException("Trying to render to a NULL response object");
        
        $data = array_merge($this->vars, $vars);
        $html = $this->getTwig()->render($template, $data);
        $this->response->getBody()->write($html);
        $newResponse = $this->response->withHeader
        (
            'Content-type',
            'text/html; charset=utf-8'
        );
        
        return $newResponse;    
    }
    
    /**
     * {@inheritDoc}
     * @throws NullResponseException
     */
    public function renderBlock(string $template, string $block, array $vars = array()): ResponseInterface
    {
        if ($this->response === null)
            throw new NullResponseException("Trying to render to a NULL response object");
        
        $data = array_merge($this->vars, $vars);
        $html = $this->getTwig()->loadTemplate($template)->renderBlock($block, $data);
        $this->response->getBody()->write($html);
        $newResponse = $this->response->withHeader
        (
            'Content-type',
            'text/html; charset=utf-8'
        );
        return $newResponse;   
    }
    
    /**
     * Returns the Twig Environment
     * 
     * @return Environment
     */
    public function getTwig() : Environment
    {
        return $this->twig;
    }
    
    /**
     * Sets the Twig Environment
     * 
     * @param Environment $twig
     */
    public function setTwig(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * {@inheritDoc}
     */
    public function renderBlockWithoutResponse($template, array $vars = array()): string
    {
        $data = array_merge($this->vars, $vars);
        return $this->getTwig()->loadTemplate($template)->renderBlock($template, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function renderWithoutResponse($template, array $vars = array()): string
    {
        $data = array_merge($this->vars, $vars);
        return $this->getTwig()->render($template, $data);
    }
}
