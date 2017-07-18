<?php
namespace UPhp\Web;

/**
 * ActiveRecord
 * @package Uphp\web
 * @link api.uphp.io
 * @since 0.0.1
 * @author Renan Valente <renan.a.valente@gmail.com>
 */
class ActiveRecord
{
    public function __construct() {
        $config = require("config/application.php");
        var_dump($config);die;

        if ($drive == 'mysql')
            return new \UPhp\MySql\ActiveRecord($model);
        elseif ($drive == 'mongodb')
            return new \UPhp\MongoDB\ActiveRecord($model);
    }
}