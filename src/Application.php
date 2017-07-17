<?php
namespace Uphp\web;

use \UPhp\ActionController\ActionController;

class Application
{
    public static $appConfig;

    public function __construct()
    {
        set_exception_handler("src\uphpExceptionHandler");
        set_error_handler("src\uphpErrorHandler");
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
        $this->requireAllDir("vendor/uphp/", true);
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
        self::$appConfig = $config;
    }

    private function requireAllDir($path, $requireVendor = false)
    {
        if (is_dir($path)) {
            $directory = dir($path);
            while ($file = $directory->read()) {
                if ($file != "." && $file != "..") {
                    if (is_dir($path . $file)) {
                        if ($requireVendor) {
                            $this->requireAllDir($path . $file . "/initializers/");
                        } else {
                            $this->requireAllDir($path . $file . "/");
                        }
                    } else {
                        if (file_exists($path . $file)) {
                            require($path . $file);
                        }
                    }
                }
            }
            $directory->close();
        }
    }
}
