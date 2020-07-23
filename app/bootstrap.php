<?php
    //Load Config Class
    require_once('config/config.php');
    //Load url_helper.php
    require_once('helpers/url_helper.php');
    require_once('helpers/session_helper.php');
    //Load Libraries Classes (One by One)
    // require_once('libraries/Core.php');
    // require_once('libraries/Controller.php');
    // require_once('libraries/Database.php');
    //Autoload All Libraries Classes {Note: File name must match Class name}
    spl_autoload_register(function($classname) {
        require_once('libraries/'. $classname .'.php');
    });