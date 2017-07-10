<?php
namespace Uphp\web;

use src\Inflection;
use \UPhp\ActionDispach\Routes as Route;
use \UPhp\ActionController\ActionController;

class Application
{
    public static $appConfig = [];
    public static $templateConfig = [];

    public function __construct()
    {
        //set_exception_handler("src\uphpExceptionHandler");
        //set_error_handler("src\uphpErrorHandler");
    }

    public function start($config)
    {
        //carregando os initializers
        $this->loadAppConfig();
        $this->getInitializersFiles();
        $this->getRoutes();
        $this->getLang();
        ActionController::callController($config);
    }

    private function getInitializersFiles()
    {
        $this->requireAllDir("config/initializers/");
    }

    private function getRoutes()
    {
        return require("config/routes.php");
    }

    private function getLang()
    {
        $this->requireAllDir("app/languages/");
    }

    private function loadAppConfig(){
        $config = require("config/application.php");
        if (isset($config["template"])) {
            $templateConfig = require("config/" . Inflection::tableize($config["template"]) . ".php");
            self::$templateConfig = $templateConfig;
        }
        self::$appConfig = $config;
    }

    private function requireAllDir($path)
    {
        $directory = dir($path);
        while ($file = $directory -> read()) {
            if ($file != "." && $file != "..") {
                require($path . $file);
            }
        }
        $directory->close();
    }
}
