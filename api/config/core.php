<?php
class Core
{

    function __construct()
    {
        try {
            session_start();

            # We load the environment values
            $this->loadEnv();
            
            # Incluimos todas las dependencias que ocupa la plataforma
            $this->loadIncludes();
            
            # Si la session dió Ok cargamos datos
            DataController::loadData();
            
            # Controlador de peticiones
            RequestController::Hook();
        } catch (\Exception $e) {
            session_destroy();
        }
    }

    private function loadIncludes()
    {
        try {
            #   [Alias]
            #   Precargamos todos los alias primero por si precisamos alguna función en algun proceso core.
            include("alias.php");

            #   [DB Config]
            #   Cargamos todas las constantes personalizadas para la plataforma 
            require_once 'config_local.php';

            #   [Config]
            #   Cargamos todas las constantes personalizadas para la plataforma 
            require_once 'defines.php';

            #   [Servicios]
            #   Cargamos todos los servicios antes que los repositorys
            require_once './src/Services/Helper.php';
            require_once './src/Services/Response.php';
            require_once './src/Services/Request.php';

            #   [Entitys]
            #   Enlazamos todas las entidades
            require("./src/Entitys/User.php");

            #   [Controllers]
            #   Enlazamos todos los controladores
            include("./src/Controller/DataController.php");
            include("./src/Controller/RequestsController.php");
            include("./src/Controller/EmailController.php");


            #   [Class]
            #   Enlazamos todas las classes globales
            require("./src/Class/DB.php");
            require("./src/Class/Logger.php");
            require("./src/Class/DependencyConstructor.php");
            require("./src/Class/PDF.php");
        } catch (Exception $e) {
            
        }
    }

    private function loadEnv()
    {
        try {
            require_once("./vendor/autoload.php");
            $dotenv = Dotenv\Dotenv::createImmutable('./../');
            $dotenv->load();
        } catch (Exception $e) {
            // require_once './src/Class/Logger.php';
            // Logger::error('CORE', 'Error in loadEnv -> ' . $e->getMessage());
        }
    }
}

$Core = new Core();
