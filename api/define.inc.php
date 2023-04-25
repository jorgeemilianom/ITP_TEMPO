<?php

define('VERSION', '1.0.2');
define('PROD', true);
define('PROTOCOLE_SECURE', true);


# Config en base a los define's principales
define('CONFIG', 'config_local.php');
define('PROTOCOLE', PROTOCOLE_SECURE ? 'https://': 'http://');
define('HOST_REACT', 'http://localhost:5173');
define('DEBUG_MODE', true);    // Poner en true para usar las dependencias desde Dist

# URL PATHS
if(PROD){
    error_reporting(1);
}

define('DIR_UPLOAD', __DIR__ . '/app/upload');
