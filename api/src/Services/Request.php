<?php

class Request {


    public static function getLocal(string $key, $callback){

        if(array_key_exists($key, $_REQUEST)){
            $callback();
        }

    }

}