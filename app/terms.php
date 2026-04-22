<?php 
//----INCLUDE APIS------------------------------------
include("api/apiLinks.php");
//----PAGE GENERATION LOGIC---------------------------
function createPage()
{
    //Page-Specific Static Content
    $terms_of_service = file_get_contents(BASE_PATH . "/data/static/terms_of_service.html");

$tcontent = <<<PAGE
            <div class="terms-content">
                {$terms_of_service}
            </div>
        
PAGE;
return $tcontent;
}

//----BUSINESS LOGIC---------------------------------
//Start up a PHP Session for this user.
session_start();

//Build up our Dynamic Content Items. 
$tpagetitle = "Home Page";
$tpagelead  = "";
$tpagecontent = createPage();
$tpagefooter = "";


//----BUILD OUR HTML PAGE----------------------------
//Create an instance of our Page class
$tpage = new MasterPage($tpagetitle);
//Set the Three Dynamic Areas (1 and 3 have defaults)
if(!empty($tpagelead))
    $tpage->setRegion('top', $tpagelead);
$tpage->setRegion('main', $tpagecontent);
if(!empty($tpagefooter))
    $tpage->setRegion('footer', $tpagefooter);
//Return the Dynamic Page to the user.    
$tpage->renderPage();
?>