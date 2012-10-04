<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'markdown.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'IgniRenderer.php';

class MarkdownRenderer implements IgniRenderer
{
    public $fileExtension;

    public function __construct($fileExtension = '.md')
    {
        $this->fileExtension = $fileExtension;
    }
    
    public function renderFile($file)
    {
        return Markdown(file_get_contents($file));
    }

    public function renderText($text)
    {
        return Markdown($text);
    }

    public function renderTemplate($template, array $sections)
    {
        foreach ($sections as $tag => $content) {
            $template = str_replace('{{' . $tag . '}}', $content, $template);
        }

        return $template;
    }

    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    public function setFileExtension($fileExtension)
    {
        $this->fileExtension = $fileExtension;
    }
}
