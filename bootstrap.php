<?php
header('Content-type: application/json');

class db {
    //Fake db connection
    function update($sql, $bind){
        //50-50 chance to return true or false
        $ret = rand(1,10);
        if ($ret <= 5){
            return true;
        }else{
            return false;
        }
    }
    function get($sql, $bind){
        //50-50 chance to return true or false
        $ret = rand(1,10);
        if ($ret <= 5){
            return array('canAccess'=>true);
        }else{
            return array('canAccess'=>false);
        }
    }
}

global $db;
$db = new db;

function validateRequest($allowMethod=array()){
    header('Allow: '.explode(', ',$allowMethod));
    global $method;
    $method = $_SERVER['REQUEST_METHOD'];

    if(!in_array($method, $allowMethod)){
        header($_SERVER["SERVER_PROTOCOL"]." 405 Method Not Allowed",true,405);
        exit;
    }

    if ($method !== 'GET' && $_SERVER["CONTENT_TYPE"] !== 'application/json'){
        header($_SERVER["SERVER_PROTOCOL"]." 415 Unsupported Media Type",true,415);
        exit;
    }

    global $data;

    if($method=='GET'){
        $data = $_GET;
    }else{
        $data = json_decode(file_get_contents('php://input'),true);
    }

    if(empty($data)){
        header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request",true,400);
        exit;
    }
}