<?php

// phpcs:disable PSR1.Files.SideEffects
// Loads config and necessary stuff
require 'config.php';
$basispad = BASE_URL;
require ABSPATH . '/vendor/autoload.php';
define('IMAGE_FOLDER', ABSPATH . "/uploads/images");
define('THUMB_FOLDER', ABSPATH . "/uploads/thumbs");
define('ADMIN_ERROR', "<br>Deze pagina is voor admins bedoeld.<br>");
define('DUPLICATE_ERROR', "Deze afbeedling is al geüpload");
define('GVISION_MACHINE_LEARNING', true);
define('GV_REQUESTS_LIMIT', 900);
define('CLOSED', false); //Allow uploads (true = geen uploads)
header("Access-Control-Allow-Origin: *");
DB::$user = DB_USER;
DB::$password = DB_PASS;
DB::$dbName = DB_DB;
DB::$host = DB_HOST;
require 'formfunctions.php';
require 'uploadfunctions.php';
require 'dashboardfunctions.php';
require 'session.php';
// DB::$logfile = ABSPATH . '/db_logfile.txt';
$session = new Session();

if (!function_exists('is_countable')) {

    function is_countable($var)
    {
        return (is_array($var) || $var instanceof Countable);
    }
}
