<?php 
//----INCLUDE APIS------------------------------------
include("api/apiLinks.php");
//----PAGE GENERATION LOGIC---------------------------

function createPage()
{
    
    //Construct the Page
$tcontent = <<<PAGE
<section class="row details">
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h3 class="card-title mb-0">Spending by Category</h3>
        </div>
        <div class="card-body chart-doughnut-wrap">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title mb-0">Monthly Income vs Expenses</h3>
        </div>
        <div class="card-body">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>
</section>

<script src="/mobileWebDevCW/js/reportsChart.js"></script>

PAGE;

return $tcontent;
}

//----BUSINESS LOGIC---------------------------------
//Start up a PHP Session for this user.
session_start();

//Build up our Dynamic Content Items. 


if (!isset($_SESSION['myuser']))
{
    header("Location: login.php");
    die();
}
else{

    $tpagetitle = "Reports";
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
    }
?>
