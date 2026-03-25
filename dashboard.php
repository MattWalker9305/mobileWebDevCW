<?php 
//----INCLUDE APIS------------------------------------
include("api/api.inc.php");

//----PAGE GENERATION LOGIC---------------------------

function createPage()
{
    //Get the Data we need for this page
    $smartphones   = jsonLoadAllSmartphone();
    $smartphoneshtml     = renderSmartphoneTable($smartphones);

    // $spendingOverview = jsonLoadSpendingOverview($_SESSION["myuser"] ?? "");
    // $spendingOverviewHtml = renderSpendingOverview($spendingOverview);
    
    //Construct the Page
$tcontent = <<<PAGE
<section class = "row details" id = "club-quote">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">Overview</h3>
        </div>
        <div class="panel-spending">

        </div>
        <div class="panel-body">
        {$smartphoneshtml}
        </div>
    </div>
</section>

PAGE;

return $tcontent;
}

//----BUSINESS LOGIC---------------------------------
//Start up a PHP Session for this user.
session_start();

//Build up our Dynamic Content Items. 
$tpagetitle = "Dashboard";
$tpagelead  = "";
$tpagecontent = createPage();
$tpagefooter = "";

if (!isset($_SESSION['myuser']))
{
    header("Location: login.php");
    die();
}
else{
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
    }
?>
