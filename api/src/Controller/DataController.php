<?php

class DataController
{

    public static function loadData()
    {
        // $_SESSION['User'] = new User();

    }

    public static function setUser()
    {
        try {
            $username = $_REQUEST['user'];
            $dataUser = DB::get(['*'], 'users', ['user' => $username]);
            if (!$dataUser) {
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

    public static function cargarHoras()
    {
        try {
            [
                'day' => $day,
                'ticket' => $ticket,
                'user' => $user,
                'horas' => $horas,
            ] = $_REQUEST;

            $dataUser = DB::get(['*'], 'users', ['user' => $user]);
            if (!$dataUser) {
                Response::json([
                    'status' => false,
                    'message' => 'El usuario no está registrado',
                    'data' => []
                ], 400);
            }
            $dataUser = reset($dataUser);

            $dataTicket = DB::get(['*'], 'us', ['name' => $ticket]);
            if (!$dataTicket) {
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

            if (!$status) {
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

    public static function getData()
    {
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
            $daysAvaible = date("t") + 1;   // Cantidad de dias disponibles este mes
            $arrayDataDays = [];
            $dias_adicionales = [];
            for ($i = 1; $i <= $daysAvaible; $i++) {  // Creamos arreglo con tamaño de $daysAvaible
                $name_day = self::nombreDia(date("N", mktime(0, 0, 0, $mesActual, $i, $year)));
                if ($i == 1) {
                    $dias_ad = date("N", mktime(0, 0, 0, $mesActual, $i, $year));
                    for ($j = $dias_ad; $j > 0; $j--) {
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

            foreach ($dataHours as $hour) {
                $i = $hour['day'];
                $id_us = $hour['id_us'];
                $dataUs = DB::findById('us', $id_us);
                $dataUs['id_hour'] = $hour['id'];
                $arrayDataDays[$i]['tickets'][] = $dataUs;
                $arrayDataDays[$i]['hours'] += $hour['hs'];
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
                    'total_horas' => $total_horas,
                    'role' => $dataUser['role_id'] == 2 ? 'TL' : 'Dev'
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

    public static function nombreDia($num)
    {
        switch ($num) {
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

    public static function generarReporte()
    {
        # Data User
        $Users = DB::get(['*'], 'users');

        self::generate_pdf($Users);
    }

    /**
     * Method for generate pdf document with products prices
     */
    private static function generate_pdf($Users)
    {
        try {
            $mesActual = date("m");
            $year = date("Y");
            # PDF y configuración base
            $pdf = new PDF('l');
            $pdf->AddPage();
            $pdf->AliasNbPages();
            $pdf->SetFont('Arial', 'B', 8);
            foreach ($Users as $User) {
                $horasTotales = 0;
                $idUser = $User['id'];
                $Hours = DB::query("SELECT * FROM hours WHERE id_user = $idUser AND MONTH(date_us) = $mesActual AND year(date_us) = $year", 1);
                
                # Header
                $pdf->Cell(100, 10, $User['user'], 1);
                $pdf->Ln();
                # Fechas
                $daysAvaible = date("t");   // Cantidad de dias disponibles este mes
                # Primer fila
                $pdf->Cell(50, 10, 'Dias', 1);
                for ($i = 1; $i <= $daysAvaible; $i++) {  // Creamos arreglo con tamaño de $daysAvaible
                    $name_day = self::nombreDia(date("N", mktime(0, 0, 0, $mesActual, $i, $year)));
                    if ($name_day == 'Sabado' || $name_day == 'Domingo') {
                        continue;
                    }
                    $pdf->Cell(10, 10, $i, 1);
                }
                $pdf->Ln();
                # Algoritmo de ordenamiento para agrupar las horas por US
                $HoursOrderByUS = [];
                foreach ($Hours as $key => $hour) {
                    $id_us = $hour['id_us'];
                    $day = $hour['day'];
                    $HoursOrderByUS[$id_us][$day] = $hour;
                    $horasTotales += $hour['hs']; 
                }
                //var_dump($horasTotales);
                $HoursOrderByHS = [];
                foreach ($HoursOrderByUS as $id_us => $hour) {
                    for ($i = 1; $i <= $daysAvaible; $i++) {
                        $name_day = self::nombreDia(date("N", mktime(0, 0, 0, $mesActual, $i, $year)));
                        if ($name_day == 'Sabado' || $name_day == 'Domingo') {
                            continue;
                        }
                        $HoursOrderByHS[$id_us][$i] = 0;
                    }
                }
                foreach ($HoursOrderByHS as $id_us => &$days) {
                    foreach ($days as $day => &$hour) {
                        $hour += isset($HoursOrderByUS[$id_us][$day]['hs']) ? $HoursOrderByUS[$id_us][$day]['hs'] : 0;
                    }
                }

                # Recorremos las US con horas cargadas
                foreach ($HoursOrderByHS as $id_us => $hours) {
                    $name_us = DB::get(['name'], 'us', ['id' => $id_us]);
                    $name_us = reset($name_us);
                    $name_us = $name_us['name'];
                    $name_us = explode(' ', $name_us);
                    $name_us = $name_us[0];

                    $pdf->Cell(50, 10, $name_us, 1);
                    # Recorremos las horas 
                    foreach ($hours as $day => $hour) {
                        $pdf->Cell(10, 10, $hour, 1);
                        # Una fila por cada hora cargada
                    }
                    $pdf->Ln();
                }
                $pdf->Cell(50, 10, 'Total de horas mensuales: '.$horasTotales, 1);
                $pdf->Ln();
                $pdf->AddPage();
            }

            $pdf->Output('', '', true);
        } catch (Exception $e) {
            Logger::error('DataController', 'Error in generate_pdf -> ' . $e->getMessage());
        }
    }


    public static function removeHsUser()
    {
        $id = $_POST['remove_hs_id'];
        DB::deleteById('hours', $id);

        Response::json([
            'status'=> true,
            'message'=> 'Hora eliminada!',
            'data' => []
        ], 200);
    }
}
