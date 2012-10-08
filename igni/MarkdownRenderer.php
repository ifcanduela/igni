<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'markdown.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'IgniRenderer.php';

class MarkdownRenderer extends IgniRenderer
{
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
}
