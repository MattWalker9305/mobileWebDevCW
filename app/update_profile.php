<?php
session_start();
include("api/apiLinks.php");

header("Content-Type: application/json");

if (empty($_SESSION['myuser'])) {
    echo json_encode(["success" => false, "message" => "Not logged in"]);
    die();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    die();
}

$loggedInUser = jsonLoadOneUser($_SESSION['myuser']);

$fname           = trim($_POST["fname"] ?? "");
$lname           = trim($_POST["lname"] ?? "");
$defaultCurrency = trim($_POST["defaultCurrency"] ?? "");

if (empty($fname) || empty($lname) || empty($defaultCurrency)) {
    echo json_encode(["success" => false, "message" => "All fields are required"]);
    die();
}

$loggedInUser->setFname($fname);
$loggedInUser->setLname($lname);
$loggedInUser->setDefaultCurrency($defaultCurrency);

jsonUpdateUser($loggedInUser);

echo json_encode(["success" => true, "message" => "Profile updated successfully"]);
?>
