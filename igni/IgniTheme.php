<?php

namespace igni;

class IgniTheme
{
    private $themeFolderLocation;
    private $pageTemplate;
    private $postTemplate;

    public function __construct($themeFolderLocation)
    {
        if (!realpath($themeFolderLocation)) {
            throw new \RuntimeException("Theme folder does not exist: $location");
        }

        $this->setup($themeFolderLocation);
    }

    public function setup($themeFolderLocation)
    {
        $this->themeFolderLocation = $themeFolderLocation;

        $pageTemplate = $this->themeFolderLocation . DS . 'page.php';
        if (!realpath($pageTemplate)) {
            throw new \RuntimeException('Template does not exist: page.php');
        }

        $this->pageTemplate = $pageTemplate;
        
        $this->postTemplate = $this->themeFolderLocation . DS . 'post.php';
        if (!realpath($this->postTemplate)) {
            $this->postTemplate = $this->pageTemplate;
        }
    }

    public function getPageTemplate()
    {
        return $this->pageTemplate;
    }

    public function getPostTemplate()
    {
        return $this->postTemplate;
    }
}
