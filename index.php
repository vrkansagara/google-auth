<?php
date_default_timezone_set('Asia/Kolkata');
require_once __DIR__ . '/vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setAuthConfig('client_secrets.json');
$client->addScope(Google_Service_Drive::DRIVE_METADATA_READONLY);

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {

    $access_token = $_SESSION['access_token']['access_token'];
    $token_type = $_SESSION['access_token']['token_type'];
    $created = $_SESSION['access_token']['created'];
    $expires_in = $_SESSION['access_token']['expires_in'];

    echo 'Your access token is =>'. $access_token .'<br>';
    echo 'Created at =>'. date('y-m-d H:i:s',(int) $created).'<br>';
    echo 'Expire at =>'. date('H:i:s',(int) strtotime($expires_in) ).'<br>';

    $client->setAccessToken($_SESSION['access_token']);
    $drive = new Google_Service_Drive($client);
    foreach ($drive->files->listFiles()->getFiles() as $key => $object){
        echo $object->getName().'<br>';
    }
} else {
    $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
