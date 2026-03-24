<?php
//Include the Other Layers Class Definitions
require_once("oo_bll.inc.php");

//---------JSON HELPER FUNCTIONS-------------------------------------------------------

function jsonOne($pfile,$pid)
{
    $tsplfile = new SplFileObject($pfile);
    $tsplfile->seek($pid-1);
    $tdata = json_decode($tsplfile->current());
    return $tdata;
}

function jsonAll($pfile)
{
    $tentries = file($pfile);
    $tarray = [];
    foreach($tentries as $tentry)
    {
        $tarray[] = json_decode($tentry);
    }
    return $tarray;
}

function jsonNextID($pfile)
{
    $tsplfile = new SplFileObject($pfile);
    $tsplfile->seek(PHP_INT_MAX);
    return $tsplfile->key() + 1;
}

//---------ID GENERATION FUNCTIONS-------------------------------------------------------

function jsonNextPlayerID()
{
    return jsonNextID("data/json/players.json");
}

//---------USER FUNCTIONS-------------------------------------------------------

function jsonSaveUser($user)
{
    $users = jsonLoadAllUsers();

    $user_data = [
        'email' => $user->getEmail(),
        'fname' => $user->getFname(),
        'lname' => $user->getLname(),
        'username' => $user->getUsername(),
        'password' => $user->getPassword()
    ];

    $users[] = $user_data;
    file_put_contents("data/json/users.json", json_encode($users, JSON_PRETTY_PRINT));
}

function jsonLoadAllUsers()
{
    $file_path = 'data/json/users.json';
    if (!file_exists($file_path)) {
        $dir = dirname($file_path);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($file_path, json_encode([]));
    }
    $json_data = file_get_contents($file_path);
    if ($json_data === false) {
        return [];
    }
    $users_data = json_decode($json_data, true); // true = associative arrays
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($users_data)) {
        return [];
    }
    $users = [];
    foreach ($users_data as $user_data) {
        $user = new BLLUser();
        $user->setEmail($user_data['email']);
        $user->setFname($user_data['fname']);
        $user->setLname($user_data['lname']);
        $user->setUsername($user_data['username']);
        $user->setPassword($user_data['password']);
        $users[] = $user;
    }
    return $users;
}

//---------JSON-DRIVEN OBJECT CREATION FUNCTIONS-----------------------------------------
function jsonLoadOneSmartphone($pid) : BLLSmartphone
{
    $smartphone = new BLLSmartphone();
    $smartphone->fromArray(jsonOne("data/json/smartphones.json",$pid));
    if(!empty($smartphone->desc_href))
    {
        $smartphone->desc = file_get_contents("data/html/smartphone/{$smartphone->desc_href}");
    }
    return $smartphone;
}

//--------------MANY OBJECT IMPLEMENTATION--------------------------------------------------------
function jsonLoadAllSmartphone() : array
{
    $tarray = jsonAll("data/json/smartphones.json");
    return array_map(function($a){ $tc = new BLLSmartphone(); $tc->fromArray($a); return $tc; },$tarray);
}
//---------XML HELPER FUNCTIONS--------------------------------------------------------

function xmlLoadAll($pxmlfile,$pclassname,$parrayname)
{
    $txmldata = simplexml_load_file($pxmlfile,$pclassname);
    $tarray = [];
    foreach($txmldata->{$parrayname} as $telement)
    {
        $tarray[] = $telement;
    }
    return $tarray;
}

function xmlLoadOne($pxmlfile,$pclassname)
{
    $txmldata = simplexml_load_file($pxmlfile,$pclassname);
    return $txmldata;
}

?>