<?php

require 'define.inc.php';

class Core
{

    function __construct()
    {
        try {
            session_start();
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
            require_once CONFIG;
            #   [Alias]
            #   Precargamos todos los alias primero por si precisamos alguna función en algun proceso core.
            include("alias.php");


            #   [Configuración general]
            #   Enlazamos todos los modulos requeridos
            include("./src/Security/session_validate.php");
            include("./src/Security/validations.php");
            include("./src/Security/Permissions.php");

            #   [Servicios]
            #   Cargamos todos los servicios antes que los repositorys
            require_once './src/Services/Helper.php';
            require_once './src/Services/Response.php';
            require_once './src/Services/Request.php';

            #   [Entitys]
            #   Enlazamos todas las entidades
            require("./src/Entitys/User.php");
            require("./src/Entitys/Adm.php");

            #   [Controllers]
            #   Enlazamos todos los controladores
            include("./src/Controller/DataController.php");
            include("./src/Controller/RequestsController.php");
            include("./src/Controller/EmailController.php");


            #   [Class]
            #   Enlazamos todas las classes globales
            require("./src/Class/DB.php");
            require("./src/Class/Upload.php");
            require("./src/Class/Logger.php");
            require("./src/Class/DependencyConstructor.php");
            require("./src/Class/Email.php");
            require("./src/Class/PDF.php");
        } catch (Exception $e) {
            Logger::error('CORE', 'Error in loadIncludes -> ' . $e->getMessage());
        }
    }
}

$Core = new Core();
