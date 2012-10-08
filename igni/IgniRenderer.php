<?php

class IgniRenderer
{
    protected $fileExtension;
    
    /**
     * Class constructor. Initializes default file extension.
     * 
     * @param string $fileExtension The file extension, starting with a period
     */
    public function __construct($fileExtension = '.html')
    {
        $this->fileExtension = $fileExtension;
    }

    /**
     * Renders a text.
     * 
     * @param string $text The text to render
     * @return string The output of rendering the string
     * @access public
     */
    public function renderText($text)
    {
        return $text;
    }
    
    /**
     * Renders the contents of a file.
     * 
     * @param string $file The path and name of the file to render
     * @return string The output of rendering the file
     * @access public
     */
    public function renderFile($file)
    {
        return file_get_contents($file);
    }

    /**
     * Renders a full page template.
     * 
     * @param $string $template The path and name of the template file
     * @param $array $sections Associative array with template fields and contents
     * @return string A string with a full HTML document
     * @deprecated This function belongs in the Igni core class
     */
    public function renderTemplate($template, array $sections)
    {
        foreach ($sections as $tag => $content) {
            $template = str_replace('{{' . $tag . '}}', $content, $template);
        }

        return $template;
    }

    /**
     * Get the extension of template files.
     * 
     * @return string The file extension, starting with a period
     */
    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    /**
     * Set the file extension of template files.
     * 
     * @param string $fileExtension The file extension, starting with a period
     */
    public function setFileExtension($fileExtension)
    {
        $this->fileExtension = $fileExtension;
    }
}
