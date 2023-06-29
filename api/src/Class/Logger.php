<?php
class Logger
{
    public static function error($module, $message)
    {
        $message = is_string($message) ? $message : json_encode($message);
        self::registerLog("[" . date("r") . "] Error en modulo $module : $message\r\n");
        if (MODE == 'Prod') {
            EmailController::sendMessage(['JorgeEmilianoM@gmail.com'], 'Reporte de Error', $message);
        }
    }
    public static function registerLog($errorLog)
    {
        $logFile = './Logs/errors.log';
        // Verificar si el archivo no existe y crearlo
        if (!file_exists($logFile)) {
            file_put_contents($logFile, $errorLog);
        }
        $file_errors = fopen($logFile, 'a');
        fwrite($file_errors, $errorLog);
        fclose($file_errors);
        unset($_SESSION['LoggError']);
    }

    public static function logFront()
    {
        if (!isset($_REQUEST['module']) || !isset($_REQUEST['message'])) return;

        $module = $_REQUEST['module'];
        $message = $_REQUEST['message'];
        self::error("[Front] " . $module, $message);
    }
}
