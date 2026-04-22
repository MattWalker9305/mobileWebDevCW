<?php
require_once("classes.php");

function jsonDeleteTransactionsByUserId($userId)
{
    $file_path = BASE_PATH . "/data/json/transactions.json";
    $json_data = file_exists($file_path) ? file_get_contents($file_path) : '[]';
    $transactions = json_decode($json_data, true);
    if (!is_array($transactions)) $transactions = [];

    $transactions = array_filter($transactions, fn($t) => $t['userId'] !== $userId);

    file_put_contents($file_path, json_encode(array_values($transactions), JSON_PRETTY_PRINT));
}

function jsonDeleteCategoriesByUserId($userId)
{
    $file_path = BASE_PATH . "/data/json/categories.json";
    $json_data = file_exists($file_path) ? file_get_contents($file_path) : '[]';
    $categories = json_decode($json_data, true);
    if (!is_array($categories)) $categories = [];

    $categories = array_filter($categories, fn($c) => $c['userId'] !== $userId);

    file_put_contents($file_path, json_encode(array_values($categories), JSON_PRETTY_PRINT));
}


function jsonLoadAllTransactions()
{
    $file_path = BASE_PATH . '/data/json/transactions.json';    
    if (!file_exists($file_path)) {
        $dir = dirname($file_path);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($file_path, json_encode([]));
    }
    $json_data = file_get_contents($file_path);
    if ($json_data === false) {
        return [];
    }
    $transactions_data = json_decode($json_data, true); 
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($transactions_data)) {
        return [];
    }
    $transactions = [];
    foreach ($transactions_data as $transaction_data) {
        $transaction = new Transaction();
        $transaction->setId($transaction_data['id'] ?? null);
        $transaction->setUserId($transaction_data['userId'] ?? null);
        $transaction->setName($transaction_data['name'] ?? null);
        $transaction->setDate($transaction_data['date'] ?? null);
        $transaction->setAmount($transaction_data['amount'] ?? null);
        $transaction->setType($transaction_data['type'] ?? null);
        $transaction->setCategoryName($transaction_data['categoryName'] ?? null);
        $transaction->setCurrency($transaction_data['currency'] ?? null);
        $transaction->setNotes($transaction_data['notes'] ?? null);
        $transactions[] = $transaction;
    }
    return $transactions;
}

function jsonLoadAllTransactionsForUser($puser) : array
{
    $transactions = jsonLoadAllTransactions();
    return array_filter($transactions, function($t) use ($puser) {
        return $t->getUserId() === $puser;
    });
}

function getNextTransactionId()
{
    $transactions = jsonLoadAllTransactions();
    if (empty($transactions)) return 1;
    $max = 0;
    foreach ($transactions as $t) {
        if ($t->getId() > $max) $max = $t->getId();
    }
    return $max + 1;
}

function jsonSaveTransaction($transaction)
{
    $file_path = BASE_PATH . "/data/json/transactions.json";
    $json_data = file_exists($file_path) ? file_get_contents($file_path) : '[]';
    $transactions = json_decode($json_data, true);
    if (!is_array($transactions)) $transactions = [];

    $transactions[] = [
        'id'           => $transaction->getId(),
        'userId'       => $transaction->getUserId(),
        'name'         => $transaction->getName(),
        'date'         => $transaction->getDate(),
        'amount'       => $transaction->getAmount(),
        'type'         => $transaction->getType(),
        'categoryName' => $transaction->getCategoryName(),
        'currency'     => $transaction->getCurrency(),
        'notes'        => $transaction->getNotes()
    ];

    file_put_contents($file_path, json_encode($transactions, JSON_PRETTY_PRINT));
}

function sortTransactionsByDate($transactions) {
    usort($transactions, function($a, $b) {
        $dateA = strtotime($a->getDate());
        $dateB = strtotime($b->getDate());
        return $dateB <=> $dateA; 
    });
    return $transactions;
}

function jsonDeleteTransaction($transactionId)
{
    $file_path = BASE_PATH . "/data/json/transactions.json";
    $json_data = file_exists($file_path) ? file_get_contents($file_path) : '[]';
    $transactions = json_decode($json_data, true);
    if (!is_array($transactions)) $transactions = [];

    $transactions = array_filter($transactions, function($t) use ($transactionId) {
        return $t['id'] !== $transactionId;
    });

    file_put_contents($file_path, json_encode(array_values($transactions), JSON_PRETTY_PRINT));
}
?>