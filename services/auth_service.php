<?php

require_once('./var/constants.php');

$url_auth = BACKEND_DOMAIN . 'auth.php';

function register($username, $password) {
    global $url_auth;

    $ch = curl_init($url_auth);
    $payload = json_encode( array( "username"=> $username,
            "password" =>$password,
            "method" => "register")
    );
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    if ($error) {
        return $error;
    } else {
        return $response;
    }
}

function login($username, $password) {
    global $url_auth;

    $ch = curl_init($url_auth);
    $payload = json_encode( array( "username"=> $username,
        "password" =>$password,
        "method" => "login")
    );
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    if ($error) {
        return $error;
    } else {
        return json_decode($response);
    }
}
