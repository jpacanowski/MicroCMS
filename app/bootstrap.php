<?php

// Load config
require_once 'config.php';

// Load helpers
require_once 'helpers/url_helper.php';
require_once 'helpers/slug_helper.php';
require_once 'helpers/session_helper.php';
require_once 'helpers/user_helper.php';

// Load libraries
require_once 'libraries/Router.php';
require_once 'libraries/Controller.php';
require_once 'libraries/Database.php';

//spl_autoload_register(function($className) {
//    require_once 'libraries/' . $className . '.php';
//});