<?php

namespace igni;

class Igni
{
    protected $template;
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

    protected $postList;
    protected $pageList;

    function __construct(IgniRenderer $renderer = null)
    {
        if ($renderer) {
            $this->renderer = $renderer;
        } else {
            $this->renderer = new IgniRenderer;
        }

        /**
         * @example  'C:\wwwroot\htdocs\igni\'
         */
        $this->path = realpath(__DIR__ . DS . '..') . DS;
        /**
         * @example 'http://localhost'
         */
        $this->serverName = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . (isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost/');
        /**
         * @example '/igni/' (length=6)
         */
        $this->serverPath = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        /**
         * @example 'http://localhost/igni/'
         */
        $this->url = $this->serverName . $this->serverPath;

        # Load the site configuration settings

        $this->config = json_decode(file_get_contents($this->path . 'config.json'));

        # Setup the application path

        $this->filesPath     = $this->path . 'site' . DS . 'files'     . DS; 
        $this->pagesPath     = $this->path . 'site' . DS . 'pages'     . DS; 
        $this->postsPath     = $this->path . 'site' . DS . 'posts'     . DS; 
        $this->themesPath    = $this->path . 'site' . DS . 'themes'    . DS; 
        $this->templatesPath = $this->path . 'site' . DS . 'templates' . DS;

        # Assign the configured theme

        $this->setTheme($this->config->theme);

        # Setup the post file list and the page file list

        $this->postList = $this->postsList();
        $this->pageList = $this->pagesList();
    }

    /**
     * Renders a full HTML document corresponding to the provided page name.
     * 
     * @param  string $page The page to render
     * @return string The rendered HTML source code
     */
    public function renderPage($page)
    {
        # A renderer is required to proceed
        
        if (!$this->renderer) {
            throw new \Exception('No renderer available');
        }

        # The URl can be empty

        if (!$page) {
            $page = '~frontPage';
        }

        # If the page starts with a tilde, it's a special page

        if ($page{0} === '~') {
            $special = substr($page, 1);
            if (isset($this->config->$special)) {
                $page = $this->config->$special;
            }
        }

        $next = $previous = null;

        # Render the main section

        if ($this->postExists($page)) {
            $main = $this->renderer->renderFile($this->getPostFileName($page));
            $next = $this->getNextPost($page);
            $previous = $this->getPreviousPost($page);
            $type = "post";
        } elseif ($this->pageExists($page)) {
            $main = $this->renderer->renderFile($this->getPageFileName($page));
            $type = "page";
        } elseif ($page === '~lastPost') {
            $lastPost = $this->postsList(1);
            $lastPost = $lastPost[0];
            $page = $lastPost['slug'];
            $main = $this->renderer->renderFile($this->getPostFileName($lastPost['slug']));
            $next = $this->getNextPost($page);
            $previous = $this->getPreviousPost($page);
            $type="post";
        } else {
            $main = $this->renderer->renderFile($this->path . 'igni' . DS . 'errors' .DS . 'article_not_found' . '.md');
            $type = "page";
        }

        if ($type === 'post') {
            ob_start();
                require $this->path . 'igni' . DS . 'widgets' . DS . 'posts_navigation.php';
                $nav = ob_get_contents();
            ob_end_clean();

            $main .= $nav;
        }

        # gather the template blocks for the theme
        $blocks = array(
                'title' => ucwords($page) . ' - ' . $this->config->siteName,
                'url'   => $this->url,
                'main'  => $main,
                'type'  => $type,
            );

        # Render the templates
        foreach (glob($this->templatesPath . DS . '*' . $this->renderer->getFileExtension()) as $template) {
            $blockName = pathinfo($template, PATHINFO_FILENAME);
            $blocks[$blockName] = $this->renderer->renderFile($template);
        }

        $templateFile = 'page';

        switch ($type) {
            case 'post':
                $templateFile = 'post';
                break; 
            default:
                $templatFile = 'page';
        }

        $template = file_get_contents($this->themesPath . $this->getTheme() . DIRECTORY_SEPARATOR. $templateFile . '.php');

        return $this->renderer->renderTemplate($template, $blocks);
    }

    /**
     * Set the renderer instance to use.
     * 
     * @param IgniRenderer $renderer An instance of a class that implements IgniRenderer
     */
    public function setRenderer(IgniRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Get the renderer instance in use.
     * 
     * @return IgniRenderer The renderer instance in use
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * Set the theme to use.
     * 
     * @param string $theme A theme subfolder in the themes folder
     */
    public function setTheme($theme)
    {
        if (!is_dir($this->themesPath . $theme)) {
            throw new \Exception("The theme {$theme} does not exist: ");
        }

        $this->theme = $theme;
    }

    /**
     * Get the current theme.
     * 
     * @return string Current theme folder name
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * The fully-qualified file name of a post.
     * 
     * This function does not check whether the file exists or not.
     * 
     * @param  string $slug The base file name, without extension
     * @return string The fully-qualified file name corresponding to the post.
     */
    public function getPostFileName($slug)
    {
        return $this->postsPath . $slug . $this->renderer->getFileExtension();
    }

    public function getPreviousPost($slug)
    {
        if (!$this->postList) {
            return null;
        } else {
            foreach ($this->postList as $key => $post) {
                if ($post['slug'] === $slug) {
                    if (isset($this->postList[$key + 1])) {
                        return $this->postList[$key + 1];
                    } else {
                        return null;
                    }
                }
            }

            return null;
        }
    }

    public function getNextPost($slug)
    {
        if (!$this->postList) {
            return null;
        } else {
            foreach ($this->postList as $key => $post) {
                if ($post['slug'] === $slug) {
                    if (isset($this->postList[$key - 1])) {
                        return $this->postList[$key - 1];
                    } else {
                        return null;
                    }
                }
            }

            return null;
        }
    }

    /**
     * The fully-qualified file name of a page.
     * 
     * This function does not check whether the file exists or not.
     * 
     * @param  string $slug The base file name, without extension
     * @return string The fully-qualified file name corresponding to the page
     */
    public function getPageFileName($slug)
    {
        return $this->pagesPath . $slug . $this->renderer->getFileExtension();
    }

    /**
     * Check if a post file exists.
     * 
     * @param  string $slug The base file name, without extension
     * @return bool True if the file exists, false otherwise
     */
    public function postExists($slug)
    {
        return file_exists($this->postsPath . $slug . $this->renderer->getFileExtension());
    }

    /**
     * Check if a page file exists.
     * 
     * @param  string $slug The base file name, without extension
     * @return bool True if the file exists, false otherwise
     */
    public function pageExists($slug)
    {
        return file_exists($this->pagesPath . $slug . $this->renderer->getFileExtension());
    }

    public function postsList($postCount = 0, $offset = 0)
    {
        static $fileList = array();

        if (!$fileList) {
            $fileList = glob($this->postsPath . '*' . $this->renderer->getFileExtension());

            if ($fileList) {
                usort($fileList, function($a, $b) {
                    return filemtime($a) > filemtime($b);
                });
            }
        }

        if ($postCount) {
            $postFiles = array_slice($fileList, $offset, $postCount);
        } else {
            $postFiles = $fileList;
        }

        array_walk($postFiles, function(&$post)
            {
                $post = array(
                    'filename' =>$post,
                    'slug' => pathinfo($post, PATHINFO_FILENAME), 
                    'title' => ucwords(str_replace(array('-', '_'), array(' ', '-'), pathinfo($post, PATHINFO_FILENAME))),
                    'date' => filemtime($post));
            });

        return $postFiles;
    }

    public function pagesList()
    {
        $pageFiles = glob($this->pagesPath . '*' . $this->renderer->getFileExtension());
        
        array_walk($pageFiles, function(&$page)
            {
                $page = array(
                    'filename' => $page,
                    'slug' => pathinfo($page, PATHINFO_FILENAME), 
                    'title' => ucwords(str_replace(array('-', '_'), array(' ', '-'), pathinfo($page, PATHINFO_FILENAME))),
                    'date' => filemtime($page));
            });
        
        return $pageFiles;
    }

    public function getSlugFromFileName($filename)
    {
        return pathinfo($filename, PATHINFO_FILENAME);
    }
}
