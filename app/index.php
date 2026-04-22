<?php 
//----INCLUDE APIS------------------------------------
include("api/apiLinks.php");
//----PAGE GENERATION LOGIC---------------------------
function createPage()
{
    //Page-Specific Static Content
    $twelcome = file_get_contents(BASE_PATH . "/data/static/index_welcome.part.html");

    $tbox_info = jsonLoadAllBoxInfo(BASE_PATH . "/data/json/home_page.json");
    $tboxes = "";
    foreach ($tbox_info as $box) {
        $tboxes .= '<div class="col-md-4 d-flex">' . renderBox($box['title'], $box['description']) . '</div>';
    }

$tcontent = <<<PAGE
            <div class="hero-banner">
                <h1>Track Your Finances</h1>
                <p class="lead">Helping you make informed financial decisions</p>
                <a class="btn btn-primary" href= "login.php">Login</a>
                <a class="btn btn-secondary" href="register.php">Register</a>
            </div>
            <div class="row g-3 mt-2">
                {$tboxes}
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

if (isset($_SESSION['myuser']))
{
    header("Location: dashboard.php");
    die();
}
else
{
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
}
?>