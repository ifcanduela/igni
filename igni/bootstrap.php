<?php

define('DS', DIRECTORY_SEPARATOR);

spl_autoload_register(function($className)
    {
        $fileName = str_replace('\\', DS, $className) . '.php';

        require getcwd() . DS . $fileName;
    }
);
