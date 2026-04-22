<?php 
//----INCLUDE APIS------------------------------------
include("api/apiLinks.php");
//----PAGE GENERATION LOGIC---------------------------

function createPage()
{
    $loggedInUser = jsonLoadOneUser($_SESSION['myuser']);
    $userID = $loggedInUser->getId();

    $userTransactions = jsonLoadAllTransactionsForUser($userID ?? "");
    $userTransactions = sortTransactionsByDate($userTransactions);

    $tboxes = "";
    foreach ($userTransactions as $box) {
        $tboxes .= renderBox($box->getName(), 
                            $box->getDate(), 
                            $box->getType(), 
                            $box->getCategoryName(), 
                            $box->getCurrency(), 
                            $box->getNotes(), 
                            $box->getAmount(), 
                            TRUE, 
                            $box->getId());
    }

    // $transactionPopup = renderTransactionModal();

    //Construct the Page
$tcontent = <<<PAGE
<section class = "row details">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Transactions</h3>
            <a class="btn btn-primary" href="new_transaction.php">+ New Transaction</a>
        </div>
        <div class="card-body">
            <div class="transaction-content">
                {$tboxes}
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

    $tpagetitle = "Transactions";
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
