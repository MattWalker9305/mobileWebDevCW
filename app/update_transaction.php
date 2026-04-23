<?php
include(__DIR__ . "/api/apiLinks.php");
session_start();

if (!isset($_SESSION['myuser'])) {
    header("Location: login.php");
    die();
}

$action = $_POST['action'] ?? '';
$id     = (int)($_POST['id'] ?? 0);

if ($action === 'delete') {
    jsonDeleteTransaction($id);
} elseif ($action === 'edit') {
    jsonUpdateTransaction(
        $id,
        $_POST['myname']     ?? '',
        $_POST['mydate']     ?? '',
        $_POST['myamount']   ?? '',
        $_POST['mytype']     ?? '',
        $_POST['mycategory'] ?? '',
        $_POST['mynotes']    ?? '',
        $_POST['mycurrency'] ?? ''
    );
}

header("Location: transactions.php");
die();
?>