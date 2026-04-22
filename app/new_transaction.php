<?php 
//----INCLUDE APIS------------------------------------
include("api/apiLinks.php");
//----PAGE GENERATION LOGIC---------------------------

function createPage()
{

    $loggedInUser = jsonLoadOneUser($_SESSION['myuser']);

    $error = "";
    $success = "";
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $name = $_POST["myname"] ?? "";
        $date = $_POST["mydate"] ?? "";
        $amount = $_POST["myamount"] ?? "";
        $type = $_POST["mytype"] ?? "";
        $categoryName = $_POST["mycategory"] ?? "";
        $currency = $_POST["mycurrency"] ?? "";
        $notes = $_POST["mynotes"] ?? "";

        // Basic validation
        if (empty($name) || empty($date) || empty($amount) || empty($type) || empty($categoryName) || empty($currency)) {
            $error = "<p class='text-danger'>All fields are required.</p>";
        } else {
            if (empty($error)) {
                // Create new transaction
                $new_transaction = new Transaction();
                $new_transaction->setId(getNextTransactionId());
                $loggedInUser = jsonLoadOneUser($_SESSION['myuser']);
                $new_transaction->setUserId($loggedInUser->getId());
                $new_transaction->setName($name);
                $new_transaction->setDate($date);
                $new_transaction->setAmount($amount);
                $new_transaction->setType($type);
                $new_transaction->setCategoryName($categoryName);
                $new_transaction->setCurrency($currency);
                $new_transaction->setNotes($notes);
                jsonSaveTransaction($new_transaction);

                $success = "<p class='text-success'>Transaction created successfully!</p>";
            }
        }
    }

    $transactionForm = renderTransactionForm($loggedInUser->getDefaultCurrency(), $loggedInUser->getId());

    //Construct the Page
$tcontent = <<<PAGE
<section class = "row details" id = "club-quote">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title mb-0">New Transaction</h3>
        </div>
        <div class="card-body">
            {$transactionForm}
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

    $tpagetitle = "New Transaction";
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
