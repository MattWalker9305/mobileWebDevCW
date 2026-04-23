<?php
// Returns aggregated transaction data for the logged-in user as JSON.
// Used by reportsChart.js to populate the reports page charts.
ini_set('display_errors', 0);
ob_start();
session_start();
ob_end_clean();
header('Content-Type: application/json');

if (!isset($_SESSION['myuser'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorised']);
    exit;
}

require_once('apiLinks.php');

$user   = jsonLoadOneUser($_SESSION['myuser']);
$userId = $user->getId();

$transactions = jsonLoadAllTransactionsForUser($userId);

// Aggregate expenses by category (doughnut chart data)
$byCategory = [];

// Aggregate income and expenses by YYYY-MM (bar chart data)
$byMonth = [];

foreach ($transactions as $t) {
    $amount = (float) $t->getAmount();
    $type   = $t->getType();   // 'income' or 'expense'
    $month  = substr($t->getDate(), 0, 7); // 'YYYY-MM'

    if ($type === 'expense') {
        $cat = $t->getCategoryName() ?: 'Uncategorised';
        $byCategory[$cat] = ($byCategory[$cat] ?? 0) + $amount;
    }

    if (!isset($byMonth[$month])) {
        $byMonth[$month] = ['income' => 0, 'expense' => 0];
    }
    $byMonth[$month][$type] = ($byMonth[$month][$type] ?? 0) + $amount;
}

// Sort months chronologically
ksort($byMonth);

echo json_encode([
    'byCategory' => $byCategory,
    'byMonth'    => $byMonth,
]);
