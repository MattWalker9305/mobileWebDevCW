<?php 
//----INCLUDE APIS------------------------------------
include("api/apiLinks.php");
//----PAGE GENERATION LOGIC---------------------------

function createPage()
{

    $loggedInUser = jsonLoadOneUser($_SESSION['myuser']);
    $userID = $loggedInUser->getId();

    //Get the Data we need for this page
    $userTransactions = jsonLoadAllTransactionsForUser($userID ?? "");

    $totalIncome   = 0;
    $totalExpenses = 0;
    foreach ($userTransactions as $t) {
        if ($t->getType() === "income") {
            $totalIncome   += (float) $t->getAmount();
        } else {
            $totalExpenses += (float) $t->getAmount();
        }
    }
    $netProfit = $totalIncome - $totalExpenses;

    $tbox_info = [
        ["title" => "Total Income", "description" => $totalIncome],
        ["title" => "Total Expenses", "description" => $totalExpenses],
        ["title" => "Net Profit", "description" => $netProfit]
    ];

    $tboxes = "";
    foreach ($tbox_info as $box) {
        $tboxes .= '<div class="col-md-4">' . renderSummaryBox($box['title'], $box['description']) . '</div>';
    }

    $userTransactions = sortTransactionsByDate($userTransactions);
    $userTransactions = array_slice($userTransactions, 0, 5);
    
    $recentTransactionBoxes = "";
    foreach ($userTransactions as $box) {
        $recentTransactionBoxes .= renderBox($box->getName(), $box->getDate(), $box->getType(), $box->getCategoryName(), $box->getCurrency(), "",$box->getAmount());
    }

    //Construct the Page
$tcontent = <<<PAGE
<section class = "row details">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title mb-0">Dashboard</h3>
        </div>
        <div class="card-body border-bottom">
            <div class="row g-3">
                {$tboxes}
            </div>
        </div>
        <div class="card-body">
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
        $tpage->setRegion('top', $tpagelead);
    $tpage->setRegion('main', $tpagecontent);
    if(!empty($tpagefooter))
        $tpage->setRegion('footer', $tpagefooter);
    //Return the Dynamic Page to the user.    
    $tpage->renderPage();
    }
?>
