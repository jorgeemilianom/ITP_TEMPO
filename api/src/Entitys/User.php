<?php


class User
{
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
}
