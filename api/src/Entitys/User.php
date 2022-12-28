<?php


class User
{
    public $info;
    public $config;
    public $role;
    public $iniciales;
    public $id;

    function __construct()
    {
        try {
            $id_user = isset($_SESSION['id']) ?  $_SESSION['id']  : $_SESSION['User']->info['id'];
            if(!$id_user || empty($id_user) || is_null($id_user)){
                session_destroy();
                header('Location: '. APP_URL);
                die;
            }
            $data = DB::findById('users', $id_user);
            if (!$data) {
                echo 'Hubo un problema al cargar datos de User';
                throw new Exception("Variable 'data' vacia. Info id_user: " . json_encode($id_user));
            }
            $data_configuration = DB::findById('user_roles', $data['role_id']);
            if (!$data_configuration) {
                header('Location: ../index.php');
                die();
            }
            $this->role = $data_configuration['role'];
            $this->info = $data;
            $this->iniciales = $this->info['name'][0] . $this->info['lastname'][0];
            $this->id = $this->info['id'];
        } catch (Exception $e) {
            Logger::error('User', 'Error in Construnct ->' . $e->getMessage());
            session_destroy();
            header('Location: '. APP_URL);
            die();
        }
    }

    #
    #   Update
    #

    public static function updatePersonalData()
    {
        try {
            $id_user = $_SESSION['User']->info['id'];
            $name = $_POST['user_name'];
            $lastname = $_POST['user_lastname'];
            $gender = $_POST['user_gender'];
            # Validamos desde back que ningun campo esté vacío
            if (empty($name) || empty($lastname) || empty($gender)) {
                return Helper::error('No se permiten campos vacíos.');
            }

            $status = DB::update('users', [
                'name' => $name,
                'lastname' => $lastname,
                'gender' => $gender
            ], ['id' => $id_user]);

            return $status ? Helper::success('Datos actualizado!') : Helper::error('Error al actualizar los datos. [CODE user_001]');
        } catch (Exception $e) {
            Logger::error('User', 'Error in update_personal_data ->' . $e->getMessage());
        }
    }

    public function update_img_profile()
    {
        try {
            if (!isset($_FILES["user_img_profile"]) || empty($_FILES["user_img_profile"]['name'])) {
                return;
            }
            $business_name = $_SESSION['Business']->info['business'];
            $id_user = $this->info['id'];
            $user = $this->info['username'];
            $dir = "../upload/$business_name/img_profiles";
            $file = $_FILES["user_img_profile"];
            if (empty($_FILES["user_img_profile"])) {
                return Helper::error('Debes seleccionar una foto.');
            }
            $res = Upload::img($file, $dir, "$user");
            $url = $res;
            $status = DB::update('users', [
                'img_profile' => $url
            ], [
                'id' => $id_user
            ]);
            return $status ?
                Helper::success('Tu imagen de perfil ha sido actualizada!') :
                Helper::error('Hubo un error al tratar de actualizar tu foto de perfil. CODE[user_002]');
        } catch (Exception $e) {
            Logger::error('User', 'Error in update_img_profile ->' . $e->getMessage());
        }
    }

    public function change_password_user()
    {
        try {
            $current_password = $_POST['user_password'];
            $new_password = $_POST['user_new_password'];
            $new_password_repeat = $_POST['user_new_password_repeat'];
            $idUser = $_SESSION['User']->id;
            // Validamos que las contraseñas nuevas coincidan 
            if ($new_password != $new_password_repeat) {
                return Helper::error('Al repetir la contraseña nueva, ambas deben coincidir.');
            }
            // Validamos que la nueva contraseña sea diferente a la actual
            if ($current_password == $new_password) {
                return Helper::error('La nueva contraseña no debe ser igual a la antigua.');
            }
            // Encriptamos la nueva contraseña
            $passwordHash = password_hash($new_password, PASSWORD_BCRYPT);
            $status = DB::update('users', ['password' => $passwordHash], [
                'id' => $idUser
            ]);
            return $status ? Helper::success('Clave de acceso actualizada.') : Helper::error('Hubo un error al querer actualizar tu clave de acceso.');
        } catch (Exception $e) {
            Logger::error('User', 'Error in change_password_user ->' . $e->getMessage());
        }
    }
}
