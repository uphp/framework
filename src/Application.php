<?php
namespace UPhp\Web;

use src\Inflection;
use \UPhp\ActionController\ActionController;
use UPhp\ActionDispach\Routes;

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
        $this->getDB();

        $route = Routes::getControllerAction($config);
        self::$appConfig["controllerName"] = Inflection::classify(Inflection::singularize($route["CONTROLLER"]));
        self::$appConfig["actionName"] = $route["ACTION"];

        ActionController::callController($config);
    }

    public function context()
    {
        return $this;
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

    private function loadAppConfig()
    {
        $config = require("config/application.php");
        $config["models"] = $this->getModels();
        self::$appConfig = $config;
    }

    private function getModels()
    {
        $path = "app/models";
        $arrModels = [];
        if (is_dir($path)) {
            $directory = dir($path);
            while ($file = $directory->read()) {
                $arrModels[] = explode(".", $file)[0];
            }
        }
        return $arrModels;
    }

    private function getDB()
    {
        if (! isset(self::$appConfig["DB"]) || self::$appConfig["DB"] === true) {
            \ActiveRecord\adapters\MysqlAdapter::connect();
        }
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

    public function __call($name, $arguments)
    {
        if (array_key_exists($name, self::$appConfig)) {
            return self::$appConfig[$name];
        } else {
            throw new OptionConfigNotExists();
        }
    }
}
