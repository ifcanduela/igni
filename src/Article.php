<?php

namespace home;

use cebe\markdown\GithubMarkdown;
use Symfony\Component\Yaml\Yaml;

/**
 * An Article represents a Markdown file and its front matter information.
 */
class Article
{
    /** @var array */
    protected $properties = [];

    /** @var string */
    public $markdown;

    /** @var string */
    public $slug;

    /** @var int */
    public $timestamp;

    /**
     * @param string $filename
     */
    public function __construct($filename = null)
    {
        if ($filename) {
            $this->openFile($filename);
        }
    }

    /**
     * @param string $filename
     */
    public function openFile($filename)
    {
        $this->slug = pathinfo($filename, PATHINFO_FILENAME);
        $this->timestamp = filemtime($filename);

        $txt = trim(file_get_contents($filename), " \t\n\r\l\v\h");

        if ('---' === substr($txt, 0, 3)) {
            $chunks = explode('---', $txt, 3);
            $frontMatter = $chunks[1];
            $this->properties = static::processFrontMatter($frontMatter);
            $this->markdown = $chunks[2];
        } else {
            $this->markdown = $txt;
        }

        if (!$this->created) {
            $this->created = date("Y-m-d", filectime($filename));
        }

        if (!$this->updated) {
            $this->updated = date("Y-m-d", filemtime($filename));
        }
    }

    /**
     * @return bool
     */
    public function isInTheFuture()
    {
        return strtotime($this->created) > time();
    }

    /**
     * @return string
     */
    public function getBackgroundImage()
    {
        $img = $this->splash ?: ($this->banner ?: '');

        return $img ? ("background-image: url('" . thumb($img, 320, 240) . "')") : '';
    }

    /**
     * @param string $prop
     * @return mixed
     */
    public function __get($prop)
    {
        if ($prop === 'html' && !array_key_exists('html', $this->properties)) {
            $parser = new GithubMarkdown();
            $this->properties['html'] = $parser->parse($this->markdown);
        }

        if (array_key_exists($prop, $this->properties)) {
            return $this->properties[$prop];
        }

        return null;
    }

    /**
     * @param string $frontMatter
     * @return array
     */
    protected static function processFrontMatter($frontMatter)
    {
        $properties = YAML::parse($frontMatter);

        return $properties ?: [];
    }
}
