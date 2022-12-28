<?php

function login(){
    if((isset($_POST['user']) && isset($_POST['pass']))|| isset($_SESSION['Google_Oauth'])){
        
        require('./config/'.CONFIG);
        $data = false;
        if(isset($_POST['user']) && isset($_POST['pass'])){
            $usuario = $_SESSION['connection']->real_escape_string($_POST['user']);
            $pass = $_SESSION['connection']->real_escape_string($_POST['pass']);
    
            $RES = mysqli_query($_SESSION['connection'], "SELECT id, password FROM user WHERE username = '$usuario'  OR email = '$usuario'");
            $data = $RES->fetch_assoc();
        }
        # Google Oauth
        if(isset($_SESSION['Google_Oauth'])){
            $usuario = $_SESSION['Google_Oauth']['email'];
            $RES = mysqli_query($_SESSION['connection'], "SELECT id FROM user WHERE email = '$usuario'");
            $data = $RES->fetch_assoc();
            if(!empty($data['id']) || !is_null($data['id'])){
                $_SESSION['id'] = $data['id'];
                header('Location: pages/dashboard.php');
                return false;
            }else{
                return true;
            }
        }
        # Session local
        if(!$data && !isset($_SESSION['Google_Oauth'])){
            $_SESSION['Notification'] = Helper::error('El usuario ingresado no existe');
        }else{
            $password = $data['password'];
            $id_user = $data['id'];
            
            if(password_verify($pass, $password)){
                $_SESSION['id'] = $id_user;
                header('Location: pages/dashboard.php');
                return false;
            }else { //Datos incorrectos
                header('Location: ./index.php');
                return false;
            }
        }
    }
}



function verify_login(){
    if((isset($_POST['firebase_email']) && isset($_POST['firebase_uid']))){
        
        require('./config/'.CONFIG);
        $data = false;
        $email = $_POST['firebase_email'];
        $RES = mysqli_query($_SESSION['connection'], "SELECT id, uid FROM users WHERE email = '$email'");
        $data = $RES->fetch_array(MYSQLI_ASSOC);
        if(!$data){
            $response = [
                'ok' => false,
                'message' => 'User not found'
            ];
            echo json_encode($response); die;
        }
        if($data['uid'] == $_POST['firebase_uid']){
            
            $_SESSION['id'] = $data['id'];
            $response = [
                'ok' => true,
                'message' => 'User logged in successfully'
            ];
            echo json_encode($response); die;
        }else{
            $response = [
                'ok' => false,
                'message' => 'User not logged'
            ];
            echo json_encode($response); die;
        }

    }
}