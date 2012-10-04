<?php

defined('DS') or define('DS', DIRECTORY_SEPARATOR);

require_once __DIR__ . DS . 'IgniRenderer.php';

class Igni
{
    protected $renderer;
    protected $theme;
    protected $config;

    protected $path;
    protected $serverName;
    protected $serverPath;
    protected $url;

    protected $filesPath;
    protected $pagesPath;
    protected $postsPath;
    protected $themesPath;
    protected $templatesPath;

    protected $_url;

    function __construct(IgniRenderer $renderer = null)
    {
        if ($renderer) {
            $this->renderer = $renderer;
        } else {
            $this->renderer = new NullRenderer;
        }

        /**
         * @example  'C:\wwwroot\htdocs\igni\'
         */
        $this->path = realpath(__DIR__ . DS . '..') . DS;
        /**
         * @example 'http://localhost'
         */
        $this->serverName = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['SERVER_NAME'];
        /**
         * @example '/igni/' (length=6)
         */
        $this->serverPath = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        /**
         * @example 'http://localhost/igni/'
         */
        $this->url = $this->serverName . $this->serverPath;

        $this->config = json_decode(file_get_contents($this->path . 'config.json'));

        $this->filesPath     = $this->path . 'site' . DS . 'files'     . DS; 
        $this->pagesPath     = $this->path . 'site' . DS . 'pages'     . DS; 
        $this->postsPath     = $this->path . 'site' . DS . 'posts'     . DS; 
        $this->themesPath    = $this->path . 'site' . DS . 'themes'    . DS; 
        $this->templatesPath = $this->path . 'site' . DS . 'templates' . DS;

        $this->setTheme($this->config->theme);
    }

    public function renderPage($page = '~frontPage')
    {
        if (!$this->renderer) {
            throw new Exception('No renderer available');
        }

        $theme = file_get_contents($this->themesPath . $this->theme . '.php');

        # Render the main section
        
        # If the page starts with a tilde, it's a special page
        if ($page{0} === '~') {
            $special = substr($page, 1);
            if (isset($this->config->$special)) {
                $page = $this->config->$special;
            }
        }

        if ($this->postExists($page)) {
            $main = $this->renderer->renderFile($this->postsPath . $page . $this->renderer->getFileExtension());
        } elseif ($this->pageExists($page)) {
            $main = $this->renderer->renderFile($this->pagesPath . $page . $this->renderer->getFileExtension());
        } elseif ($page === '~posts') {
            $this->postsLists();
        } elseif ($page === '~pages') {
            $this->pagesList();
        } else {
            $main = $this->renderer->renderFile($this->path . 'igni' . DS . 'errors' .DS . 'article_not_found' . '.md');
        }

        $header = $this->renderer->renderFile($this->templatesPath . 'header' . $this->renderer->getFileExtension());
        $sidebar = $this->renderer->renderFile($this->templatesPath . 'sidebar' . $this->renderer->getFileExtension());
        $footer = $this->renderer->renderFile($this->templatesPath . 'footer' . $this->renderer->getFileExtension());

        $title = ucwords($page) . ' - ' . $this->config->siteName;
        $url = $this->url;

        echo $this->renderer->renderTemplate($theme, compact('url', 'title', 'header', 'main', 'sidebar', 'footer'));
    }

    public function setRenderer(IgniRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Set the theme to use.
     * 
     * @param string $them A theme file in the themes folder
     */
    public function setTheme($theme)
    {
        if (!file_exists($this->themesPath . $theme . '.php')) {
            var_dump($this->themesPath . $theme . '.php');
            throw new Exception("The theme file does not exist: ");
        }

        $this->theme = $theme;
    }

    public function getPostFileName($slug)
    {
        return $this->postsPath . $slug . $this->renderer->getFileExtension();
    }

    public function getPageFileName($slug)
    {
        return $this->pagesPath . $slug . $this->renderer->getFileExtension();
    }

    public function postExists($slug)
    {
        return file_exists($this->postsPath . $slug . $this->renderer->getFileExtension());
    }

    public function pageExists($slug)
    {
        return file_exists($this->pagesPath . $slug . $this->renderer->getFileExtension());
    }

    public function postsList()
    {

    }

    public function pagesList()
    {

    }
}
