<?php
class Logger {
    public static function error($module, $message){
        $message = is_string($message) ? $message : json_encode($message);
        self::registerLog("[".date("r")."] Error en modulo $module : $message\r\n");
        if(PROD){
            EmailController::sendMessage(['JorgeEmilianoM@gmail.com'], 'Reporte de Error', $message);
        }
    }
    public static function registerLog($errorLog){
        $file_errors = fopen('../Logs/errors.log','a');
        fwrite($file_errors, $errorLog);
        fclose($file_errors);
        unset($_SESSION['LoggError']);
    }
    
    public static function logFront(){
        if(!isset($_REQUEST['module']) || !isset($_REQUEST['message'])) return;

        $module = $_REQUEST['module'];
        $message = $_REQUEST['message'];
        self::error("[Front] ".$module, $message);
    }
}