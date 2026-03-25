<?php 
//----INCLUDE APIS------------------------------------
include("api/api.inc.php");

//----PAGE GENERATION LOGIC---------------------------

function createPage()
{
    $error = "";
    $success = "";
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $users = jsonLoadAllUsers();
        $email = $_POST["myuser"] ?? "";
        $fname = $_POST["myfname"] ?? "";
        $lname = $_POST["mylname"] ?? "";
        $username = $_POST["myusername"] ?? "";
        $password = $_POST["mypassword"] ?? "";
        $confirm_password = $_POST["confirm_password"] ?? "";

        // Basic validation
        if (empty($email) || empty($fname) || empty($lname) || empty($username) ||empty($password) || empty($confirm_password)) {
            $error = "<p class='text-danger'>All fields are required.</p>";
        } elseif ($password !== $confirm_password) {
            $error = "<p class='text-danger'>Passwords do not match.</p>";
        } else {
            // Check if email already exists
            foreach ($users as $user) {
                $user = (object) $user;
                if ($user->getEmail() === $email) {
                    $error = "<p class='text-danger'>Email already registered.</p>";
                    break;
                }
            }

            if (empty($error)) {
                // Create new user
                $new_user = new BLLUser();
                $new_user->setEmail($email);
                $new_user->setFname($fname);
                $new_user->setLname($lname);
                $new_user->setUsername($username);
                $new_user->setPassword($password); // In production, hash the password
                $users[] = $new_user;

                jsonSaveUser($new_user);

                $success = "<p class='text-success'>Registration successful! <a href='login.php'>Login here</a>.</p>";
            }
        }
    }

    $formhtml = renderRegisterForm();

    $tcontent = <<<PAGE
    <section class="row details" id="register-form">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Profile</h3>
            </div>
            <div class="panel-body">
                {$error}
                {$success}
                {$formhtml}
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
$tpagetitle = "Profile Page";
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