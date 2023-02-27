<?php

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