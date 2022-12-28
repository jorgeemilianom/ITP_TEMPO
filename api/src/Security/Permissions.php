<?php

class Permissions
{

    /**
     * @param string $module Name of the module
     * @param bool $redirect Validate if redirect 
     * @param string $location Especific location to redirect
     */
    public static function get_permission($module, $redirect = false, $location = "products.php")
    {
        try {
            # Datos de modulos
            $Business_modules = $_SESSION['Business']->modules;
            $AllModulesFilterByName = $_SESSION['Business']->modulesFilterByName;
            
            # Validamos si el modulo que solicita permiso está en la lista de los modulos de la business
            if(!in_array($module, $Business_modules)){
                if ($redirect) {
                    header("Location: $location");
                } else {
                    return false;
                }
            }

            # Datos del modulo segun su nombre
            $dataModule = $AllModulesFilterByName[$module];
            
            if($dataModule['active']){
                return true;
            }

            if ($redirect) {
                header("Location: $location");
            } else {
                return false;
            }
        } catch (Exception $e) {
            Logger::error('Permission', 'Error in get_permission -> ' . $e->getMessage());
            return false;
        }
    }

    /**
     * @param string $type Name of the role type
     * @param bool $redirect Validate if redirect 
     * @param string $location Especific location to redirect
     */
    public static function getPermissionByUserType($type, $redirect = false, $location = "products.php")
    {
        try {
            $userType = $_SESSION['User']->role;
            if ($type == $userType) {
                return true;
            }
            if ($redirect) {
                header("Location: $location");
            }
    
            return false;
        } catch (Exception $e) {
            Logger::error('Permission', 'Error in getPermissionByUserType -> ' . $e->getMessage());
            if ($redirect) {
                header("Location: $location");
            }
            return false;
        }
    }
}
