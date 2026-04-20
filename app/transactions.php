<?php 
//----INCLUDE APIS------------------------------------
include("api/api.inc.php");

//----PAGE GENERATION LOGIC---------------------------

function createPage()
{
    //Get the Data we need for this page
    // $spendingOverview = jsonLoadSpendingOverview($_SESSION["myuser"] ?? "");
    // $spendingOverviewHtml = renderSpendingOverview($spendingOverview);
    $totalIncome = 500;//getTotalIncome($_SESSION["myuser"] ?? "");
    $totalExpenses = 200;//getTotalExpenses($_SESSION["myuser"] ?? "");
    $netProfit = $totalIncome - $totalExpenses;

    $loggedInUser = jsonLoadOneUser($_SESSION['myuser']);
    $userID = $loggedInUser->getId();

    $userTransactions = jsonLoadAllTransactionsForUser($userID ?? "");

    $tboxes = "";
    foreach ($userTransactions as $box) {
        $tboxes .= renderBox($box->getName(), $box->getDate(), $box->getAmount(), $box->getType(), $box->getCategoryName(), $box->getCurrency(), $box->getNotes());
    }

    //Construct the Page
$tcontent = <<<PAGE
<section class = "row details" id = "club-quote">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">Transactions</h3>
            <a class="btn btn-primary" href= "new_transaction.php">+ New Transaction </a>
        </div>
        <div class="transaction-content">
            {$tboxes}
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
