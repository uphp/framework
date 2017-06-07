<?php
namespace Uphp\web;

use \UPhp\ActionDispach\Routes as Route;
use \UPhp\ActionController\ActionController;

class Application
{
    public function __construct()
    {
        set_exception_handler("src\uphpExceptionHandler");
        set_error_handler("src\uphpErrorHandler");
    }

    public function start($config)
    {
        //carregando os initializers
        $this->getInitializersFiles();
        $this->getRoutes();
        ActionController::callController($config);
    }

    private function getInitializersFiles()
    {
        $path = "config/initializers/";
        $directory = dir($path);
        while ($file = $directory -> read()) {
            if ($file != "." && $file != "..") {
                require($path . $file);
            }
        }
        $directory->close();
    }

    private function getRoutes()
    {
        return require("config/routes.php");
    }
}
