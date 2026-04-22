<?php 
//----INCLUDE APIS------------------------------------
include("api/apiLinks.php");
//----BUSINESS LOGIC---------------------------------

//Get Existing Session
session_start();

$taction = $_REQUEST["action"] ?? "";
if($taction == "exit" && isLoggedIn())
{
    logout();
    goHome();
}
else
{
    goHome();
}