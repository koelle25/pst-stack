<?php

namespace app;

use Slim;

class TwigExtension extends \Twig_Extension
{
    /**
     * @var \Slim\Interfaces\RouterInterface
     */
    private $router;

    /**
     * @var string|\Slim\Http\Uri
     */
    private $uri;

    public function __construct($router, $uri)
    {
        $this->router = $router;
        $this->uri = $uri;
    }

    public function getName()
    {
        return 'slim';
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('path_for', array($this, 'pathFor')),
            new \Twig_SimpleFunction('base_url', array($this, 'baseUrl')),
            new \Twig_SimpleFunction('is_current_path', array($this, 'isCurrentPath')),
        ];
    }

    public function pathFor($name, $data = [], $queryParams = [], $appName = 'default')
    {
        return $this->router->pathFor($name, $data, $queryParams);
    }

    public function baseUrl()
    {
        if (is_string($this->uri)) {
            return $this->uri;
        }
        if (method_exists($this->uri, 'getBaseUrl')) {
            return $this->uri->getBaseUrl();
        }
    }

    public function isCurrentPath($name)
    {
        return $this->pathFor($name) === ($this->uri->getBasePath() . '/' . preg_replace('/\/$/', '', $this->uri->getPath()));
    }

    /**
     * Set the base url
     *
     * @param string|Slim\Http\Uri $baseUrl
     * @return void
     */
    public function setBaseUrl($baseUrl)
    {
        $this->uri = $baseUrl;
    }
}