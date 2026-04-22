<?php 
//----INCLUDE APIS------------------------------------
include("api/apiLinks.php");
//----PAGE GENERATION LOGIC---------------------------

function createPage()
{
    $error = "";
    $success = "";
    
    $userInfo = jsonLoadOneUser($_SESSION['myuser']);

    $updateForm = renderUpdateForm($userInfo);



    $tcontent = <<<PAGE
    <section class="row details" id="Profile">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title mb-0">Profile</h3>
            </div>
            <div class="card-body">
                <div class="profile-content">
                    {$updateForm}
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

if (empty($_SESSION['myuser']))
{
    header("Location: login.php");
    die();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "delete") {
    $userToDelete = jsonLoadOneUser($_SESSION['myuser']);
    if ($userToDelete) {
        jsonDeleteTransactionsByUserId($userToDelete->getId());
        jsonDeleteCategoriesByUserId($userToDelete->getId());
    }
    jsonDeleteUser($_SESSION['myuser']);
    session_destroy();
    header("Location: login.php");
    die();
}

//Build up our Dynamic Content Items.
$tpagetitle = "Profile Page";
$tpagelead  = "";
$tpagecontent = createPage();
$tpagefooter = "";


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
    $tpage->addScriptFile("updateUserInfo.js");
    $tpage->renderPage();
    }
?>