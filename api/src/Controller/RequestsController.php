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
                DataController::setUser();
            });


        } catch (Exception $e) {

        }

    }
}
