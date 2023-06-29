<?php
define('VERSION', '1.1.0');
define('VERSION_NUM', str_replace('.', '', VERSION));   //Evita conflictos en React (no acepta agregar ?v=1.8.3)

define('MODE', 'Prod');
define('DEBUG_MODE', false);    // Poner en true para usar las dependencias desde Dist

define('PROTOCOLE_SECURE', true);
define('PROTOCOLE', PROTOCOLE_SECURE ? 'https://': 'http://');

define('HOST', PROTOCOLE . "itp.gesprender.com/api/");
define('HOST_REACT', "http://localhost:5173");

if(MODE == 'Prod'){
    # Errors Display
    error_reporting(1);
}

# Paths
define('PATH_SRC', HOST.'src/');
define('PATH_LOGS', HOST.'Logs/');
define('PATH_CONFIG', HOST.'config/');
define('PATH_UPLOAD', HOST.'uploads/');
define('PATH_VENDOR', HOST.'vendor/');


#   Definimos zona horaria ARGENTINA
date_default_timezone_set('America/Argentina/Buenos_Aires');