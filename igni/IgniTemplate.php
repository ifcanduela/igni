<?php

class IgniTemplate
{
    protected $executed;
    protected $result;
    protected $template;

    public function __construct($template)
    {
        $this->setTemplate($template);
    }

    public function setTemplate($template)
    {
        if (!file_exists($template)) {
            throw new Exception('The template file does not exist');
        }

        $this->template = file_get_contents($template);
        $this->executed = false;
    }

    public function parse($data)
    {
        $this->result = '';

        foreach ($data as $tagName => $tagValue) {
            $this->result = str_replace('{{' . $tagName . '}}', $tagValue, $this->template);
        }

        $this->executed = true;

        return $this->result;
    }

    public function read()
    {
        if ($this->executed === false) {
            return false;
        }
        
        return $this->result;
    }
}
