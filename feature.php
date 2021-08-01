<?php
require_once 'bootstrap.php';
validateRequest(array('GET','POST'));
global $data;
if($method=='GET'){
    getAccess($data);
}elseif ($method=='POST') {
    updateAcess($data);
}

function getAccess($data){
    if(!isset($data['email']) || !isset($data['featureName']) || empty(trim($data['email'])) || empty(trim($data['featureName']))){
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found",true,404);
        $resp = array(
            'error' => array(
                'code'=>404,
                'message'=>'Required parameters not set (email, featureName)'
            )
        );
        echo json_encode($resp);
        exit;
    }
    
    $email = trim($data['email']);
    $feature = trim($data['featureName']);
    $bind = array(
        $email,
        $feature
    );
    
    global $db;
    $ok = $db->get('select canAccess from features where email = :0 and featureName = :1', $bind);
    echo json_encode($ok);
}

function updateAcess($data){
    if(!isset($data['email']) || !isset($data['featureName']) || !isset($data['enable'])){
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found",true,404);
        $resp = array(
            'error' => array(
                'code'=>404,
                'message'=>'Required parameters not set (featureName, email, enable)'
            )
        );
        echo json_encode($resp);
        exit;
    }
    if(gettype($data['email'])!=='string' || gettype($data['featureName'])!=='string' || gettype($data['enable'])!=='boolean'){
        header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request",true,400);
        $resp = array(
            'error' => array(
                'code'=>400,
                'message'=>'Invalid data type'
            )
        );
        echo json_encode($resp);
        exit;
    }
    $email = trim($data['email']);
    $feature = trim($data['featureName']);
    $bind = array(
        $data['enable'],
        $email,
        $feature
    );
    global $db;
    $ok = $db->update('update features set canAccess = :0 where email = :1 and featureName = :2', $bind);
    if(!$ok){
        header($_SERVER["SERVER_PROTOCOL"]." 304 Not Modified",true,304);
    }
}
