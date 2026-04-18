<?php 
//----INCLUDE APIS------------------------------------
include("api/api.inc.php");

//----PAGE GENERATION LOGIC---------------------------

function createSmartphonePage(BLLSmartphone $smartphone)
{
    $tsmartphonehtml = renderSmartphoneOverview($smartphone);
    $tcontent = <<<PAGE
    {$tsmartphonehtml}
PAGE;
    return $tcontent;
}


//----BUSINESS LOGIC---------------------------------
//Start up a PHP Session for this user.
session_start();

$tpagecontent = "";

$tid = $_REQUEST["id"] ?? -1;

//Boolean valid to check for page validity.
$tvalid = false;

if (is_numeric($tid) && $tid > 0)
    {
        $smartphone = jsonLoadOneSmartphone($tid);
        $tpagecontent = createSmartphonePage($smartphone);
        $tvalid = true;
    }

//We do not have a valid page.
if(!$tvalid)
{
    header("Location: app_error.php");
    return;
}

//If we get to here, $tvalid must be true....

//Build up our Dynamic Content Items. 
$tpagetitle = "Smartphone page";
$tpagelead  = "";
$tpagefooter = "";

//----BUILD OUR HTML PAGE----------------------------
//Create an instance of our Page class
$tpage = new MasterPage($tpagetitle);
//Set the Three Dynamic Areas (1 and 3 have defaults)
if(!empty($tpagelead))
    $tpage->setDynamic1($tpagelead);
$tpage->setDynamic2($tpagecontent);
if(!empty($tpagefooter))
    $tpage->setDynamic3($tpagefooter);
//Return the Dynamic Page to the user.    
$tpage->renderPage();
?>