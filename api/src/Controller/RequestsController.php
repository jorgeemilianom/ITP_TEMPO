<?php

class RequestController
{
    public static function Hook()
    {
        try {
            # us
            Request::getLocal('cargarHoras', function () {
                DataController::cargarHoras();
            });
            Request::getLocal('getData', function () {
                DataController::getData();
            });
            Request::getLocal('user', function () {
                User::setUser();
            });
            Request::getLocal('generatePDF', function () {
                DataController::generarReporte();
            });
            Request::getLocal('removeHsUser', function () {
                DataController::removeHsUser();
            });


        } catch (Exception $e) {

        }

    }
}
