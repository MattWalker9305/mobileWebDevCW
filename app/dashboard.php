<?php 
//----INCLUDE APIS------------------------------------
include("api/api.inc.php");

//----PAGE GENERATION LOGIC---------------------------

function createPage()
{

    $loggedInUser = jsonLoadOneUser($_SESSION['myuser']);
    $userID = $loggedInUser->getId();

    //Get the Data we need for this page
    // $spendingOverview = jsonLoadSpendingOverview($_SESSION["myuser"] ?? "");
    // $spendingOverviewHtml = renderSpendingOverview($spendingOverview);
    $totalIncome = 500;//getTotalIncome($_SESSION["myuser"] ?? "");
    $totalExpenses = 200;//getTotalExpenses($_SESSION["myuser"] ?? "");
    $netProfit = $totalIncome - $totalExpenses;

    $tbox_info = [
        ["title" => "Total Income", "description" => $totalIncome],
        ["title" => "Total Expenses", "description" => $totalExpenses],
        ["title" => "Net Profit", "description" => $netProfit]    
    ];

    $tboxes = "";
    foreach ($tbox_info as $box) {
        $tboxes .= renderBox($box['title'], $box['description']);
    }

    $userTransactions = jsonLoadAllTransactionsForUser($userID ?? "");

    $recentTransactionBoxes = "";
    foreach ($userTransactions as $box) {
        $recentTransactionBoxes .= renderBox($box->getName(), $box->getDate(), $box->getAmount(), $box->getType(), $box->getCategoryName(), $box->getCurrency(), $box->getNotes());
    }

    //Construct the Page
$tcontent = <<<PAGE
<section class = "row details">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">Dashboard</h3>
        </div>
        <div class="dashboard-content">
            {$tboxes}
        </div>
        <div class="panel-body">
            <h3 class="panel-subtitle">Recent Transactions</h3>
            <div class="transaction-actions">
                <a class="btn btn-primary" href= "new_transaction.php">+ New Transaction </a>
            </div>
            <div class="transaction-content">
                {$recentTransactionBoxes}
            </div>
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


if (!isset($_SESSION['myuser']))
{
    header("Location: login.php");
    die();
}
else{

    $tpagetitle = "Dashboard";
    $tpagelead  = "";
    $tpagecontent = createPage();
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
    }
?>
