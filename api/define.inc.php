<?php

define('VERSION', '1.0.0');
define('PROD', false);
define('PROTOCOLE_SECURE', false);


# Config en base a los define's principales
define('CONFIG', 'config_local.php');
define('PROTOCOLE', PROTOCOLE_SECURE ? 'https://': 'http://');
define('HOST_REACT', 'http://127.0.0.1:5173');
define('DEBUG_MODE', true);    // Poner en true para usar las dependencias desde Dist

# URL PATHS
if(PROD){
    error_reporting(1);
}

define('DIR_UPLOAD', __DIR__ . '/app/upload');
