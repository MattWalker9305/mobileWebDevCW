<?php

require_once("classes.php");

function sanitise($input)
{
    return htmlspecialchars(trim($input ?? ""), ENT_QUOTES, 'UTF-8');
}

function sanitiseInput($pdata)
{
    $tclean = $pdata ?? "";
    if (! empty($tclean))
    {
        $tclean = trim($tclean);
        $tclean = sanitise($tclean);
    }
    return $tclean;
}

function goHome()
{
    header("Location: index.php");
}

function isLoggedIn()
{
    $tuser = $_SESSION["myuser"] ?? "";
    return !empty($_SESSION["myuser"]);
}

function logout()
{
    session_unset();
    session_destroy();
}

function getNextUserId($users) {
    $maxId = 0;

    foreach ($users as $user) {
        if ($user->getId() !== null && $user->getId() > $maxId) {
            $maxId = $user->getId();
        }
    }

    return $maxId + 1;
}

function jsonLoadAllBoxInfo($location) : array
{
    $tjson = file_get_contents($location);
    return json_decode($tjson, true);
}
?>