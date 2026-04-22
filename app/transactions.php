<?php 
//----INCLUDE APIS------------------------------------
include("api/api.inc.php");

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
        $tpage->setDynamic1($tpagelead);
    $tpage->setDynamic2($tpagecontent);
    if(!empty($tpagefooter))
        $tpage->setDynamic3($tpagefooter);
    //Return the Dynamic Page to the user.    
    $tpage->renderPage();
    }
?>
