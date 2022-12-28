<?php

class DataController
{

    public static function loadData()
    {
        // $_SESSION['User'] = new User();

    }

    public static function setUser(){
        try {
            $username = $_REQUEST['user'];
            $dataUser = DB::get(['*'], 'users', ['user' => $username]);
            if(!$dataUser){
                Response::json([
                    'status' => false,
                    'message' => 'Usuario no registrado',
                    'data' => [$dataUser]
                ], 400);
            }
            $dataUser = reset($dataUser);

            $_SESSION['User'] = [
                'id' => $dataUser['id'],
                'name' => $dataUser['user']
            ];
            Response::json([
                'status' => true,
                'message' => 'Usuario cargado',
                'data' => $dataUser
            ], 200);
        } catch (Exception $e) {
            Response::json([
                'status' => false,
                'message' => 'Error en el servidor',
                'data' => []
            ], 500);
        }
    }

    public static function cargarHoras(){
        try {
            [
                'day' => $day,
                'ticket' => $ticket,
                'user' => $user,
                'horas' => $horas,
            ] = $_REQUEST;
    
            $dataUser = DB::get(['*'], 'users', ['user' => $user]);
            if(!$dataUser){
                Response::json([
                    'status' => false,
                    'message' => 'El usuario no está registrado',
                    'data' => []
                ], 400);
            }
            $dataUser = reset($dataUser);
            
            $dataTicket = DB::get(['*'], 'us', ['name' => $ticket]);
            if(!$dataTicket){
                DB::insert('us', ['name' => $ticket]);
                $dataTicket = DB::get(['*'], 'us', ['name' => $ticket]);
            }
            $dataTicket = reset($dataTicket);
    
            $status = DB::insert('hours', [
                'id_us' => $dataTicket['id'],
                'id_user' => $dataUser['id'],
                'day' => $day,
                'hs' => $horas
            ]);
    
            if(!$status){
                Response::json([
                    'status' => false,
                    'message' => 'Hubo un error al querer cargar las horas',
                    'data' => []
                ], 400);
            }
    
            Response::json([
                'status' => true,
                'message' => 'Horas cargadas',
                'data' => []
            ], 200);
        } catch (Exception $e) {
            Response::json([
                'status' => false,
                'message' => 'Error en el servidor',
                'data' => []
            ], 500);
        }
    }

    public static function getData(){
        try {
            $idUser = $_SESSION['User']['id'];
            #data DB
            $dataTickets = DB::get(['*'], 'us');
            $dataUser = DB::get(['*'], 'users', ['id' => $idUser]);
            $dataUser = reset($dataUser);

            $mesActual = date("m");
            $year = date("Y");
            
            $dataHours = DB::query("SELECT * FROM hours WHERE id_user = $idUser AND MONTH(date_us) = $mesActual AND year(date_us) = $year", 1);

            # Order data
            $daysAvaible = date("t")+1;   // Cantidad de dias disponibles este mes
            $arrayDataDays = [];
            $dias_adicionales = [];
            for($i=1; $i<=$daysAvaible; $i++){  // Creamos arreglo con tamaño de $daysAvaible
                $name_day = self::nombreDia(date("N", mktime(0, 0, 0, $mesActual, $i, $year)));
                if($i == 1){
                    $dias_ad = date("N", mktime(0, 0, 0, $mesActual, $i, $year)) ;
                    for($j = $dias_ad; $j > 0; $j--){
                        $dias_adicionales[] = self::nombreDia($j);
                    }
                }
                $arrayDataDays[$i] = [
                    'day' => $i,
                    'tickets' => [],
                    'hours' => 0,
                    'name_day' => $name_day,
                ];
            }

            $total_horas = 0;

            foreach ($dataHours as $hour){
                $i = $hour['day'];
                $id_us = $hour['id_us'];
                $dataUs = DB::findById('us', $id_us);
                $arrayDataDays[$i]['tickets'][] = $dataUs;
                $arrayDataDays[$i]['hours']+= $hour['hs'];
                $total_horas += $hour['hs'];
            }
            
            Response::json([
                'status' => true,
                'message' => 'global data',
                'data' => [
                    'tickets' => $dataTickets ? $dataTickets : [],
                    'user' => $dataUser,
                    'hours' => $dataHours ? $dataHours : 0,
                    'days' => $arrayDataDays,
                    'dias_ad' => array_reverse($dias_adicionales),
                    'total_horas' => $total_horas
                ]
            ], 200);
        } catch (Exception $e) {
            Response::json([
                'status' => false,
                'message' => 'Error en el servidor',
                'data' => []
            ], 500);
        }
    }


    public static function nombreDia($num){
        switch ($num){
            case 1:
                return 'Lunes';
            case 2:
                return 'Martes';
            case 3:
                return 'Miercoles';
            case 4:
                return 'Jueves';
            case 5:
                return 'Viernes';
            case 6:
                return 'Sabado';
            case 7:
                return 'Domingo';
            default:
                return false;
        }
    }

}
