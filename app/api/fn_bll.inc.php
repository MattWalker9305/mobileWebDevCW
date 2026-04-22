<?php

require_once("oo_bll.inc.php");

function appFormProcessData($pdata)
{
    $tclean = $pdata ?? "";
    if (! empty($tclean))
    {
        $tclean = trim($tclean);
        $tclean = stripslashes($tclean);
        $tclean = htmlspecialchars($tclean);
    }
    return $tclean;
}

function appGoToHome()
{
    header("Location: index.php");
}

function appGoToError()
{
    header("Location: app_error.php");
}

function appSessionLoginExists()
{
    $tuser = $_SESSION["myuser"] ?? "";
    if(!empty($tuser))
        return true;
        return false;
}

function appSessionDestroy()
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

?>
