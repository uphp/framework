<?php
    namespace Uphp\web;

    class Application
    {
        public function __construct()
        {
            set_exception_handler("src\uphpExceptionHandler");
            set_error_handler("src\uphpErrorHandler");
        }

        public function start()
        {
           //carregando os initializers
           $this->getInitializersFiles();
        }

        private function getInitializersFiles(){
            $path = "config/initializers/";
            $directory = dir($path);
            while($file = $directory -> read()){
                if ( $file != "." && $file != ".." ) {
                    require($path . $file);
                }
            }
            $directory->close();
        }
    }