<?php 
//----INCLUDE APIS------------------------------------
include("api/apiLinks.php");
//----PAGE GENERATION LOGIC---------------------------

function createPage()
{
    $error = "";
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $users = jsonLoadAllUsers();
        $email = $_POST["myuser"] ?? "";
        $password = $_POST["mypassword"] ?? "";

        foreach ($users as $user) {
            if ($user->getEmail() === $email && $user->getPassword() === $password) {
                $_SESSION["myuser"] = $email;
                $_SESSION["myname"] = $user->getFname();
                header("Location: dashboard.php");
                exit;
            }
        }
        $error = "<p class='text-danger'>Invalid login. Please try again.</p>";
    }

    $formhtml = renderLoginForm();

    $tcontent = <<<PAGE
    <section class="row details" id="login-form">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title mb-0">Login</h3>
            </div>
            <div class="card-body">
                {$error}
                {$formhtml}
            </div>
            <div class="card-footer">
                <p>Don't have an account? <a class="btn btn-outline-secondary" href="register.php">Register here</a></p>
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
$tpagetitle = "User Login";
$tpagelead  = "";
$tpagecontent = createPage();
$tpagefooter = "";

if (isset($_SESSION['myuser']))
{
    header("Location: dashboard.php");
    die();
}
else{
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