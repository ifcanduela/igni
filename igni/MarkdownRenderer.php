<?php

namespace igni;

class MarkdownRenderer extends IgniRenderer
{
    public $markdownParser;

    public function __construct($fileExtension = '.md')
    {
        $this->markdownParser = new \igni\markdown\MarkdownExtra;
        $this->fileExtension = $fileExtension;
    }
    
    public function renderFile($file)
    {
        return $this->markdownParser->transform(file_get_contents($file));
    }

    public function renderText($text)
    {
        return $this->markdownParser->transform($text);
    }
}
