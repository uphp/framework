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

        }
    }