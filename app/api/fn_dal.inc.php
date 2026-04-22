<?php
require_once("oo_bll.inc.php");
define('BASE_PATH', dirname(__DIR__, 2));

function jsonSaveUser($user)
{
    $users = jsonLoadAllUsers();

    $user_data = [
        'id'       => $user->getId(),
        'email'    => $user->getEmail(),
        'fname'    => $user->getFname(),
        'lname'    => $user->getLname(),
        'password' => $user->getPassword(),
        'defaultCurrency' => $user->getDefaultCurrency()
    ];

    $users[] = $user_data;
    file_put_contents(BASE_PATH . "/data/json/users.json", json_encode($users, JSON_PRETTY_PRINT));
}

function jsonLoadAllUsers()
{
    $file_path = BASE_PATH . '/data/json/users.json';    
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
    $users_data = json_decode($json_data, true); // true = associative arrays
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($users_data)) {
        return [];
    }
    $users = [];
    foreach ($users_data as $user_data) {
        $user = new BLLUser();
        $user->setId($user_data['id'] ?? null);
        $user->setEmail($user_data['email']);
        $user->setFname($user_data['fname']);
        $user->setLname($user_data['lname']);
        $user->setPassword($user_data['password']);
        $user->setDefaultCurrency($user_data['defaultCurrency']);
        $users[] = $user;
    }
    return $users;
}

function jsonLoadOneUser($email)
{
    $users = jsonLoadAllUsers();
    foreach ($users as $user) {
        if ($user->getEmail() === $email) {
            return $user;
        }
    }
    return null;
}

function jsonUpdateUser($updatedUser)
{
    $users = jsonLoadAllUsers();
    $updated = [];

    foreach ($users as $user) {
        if ($user->getEmail() === $updatedUser->getEmail()) {
            $updated[] = [
                'id'       => $updatedUser->getId(),
                'email'    => $updatedUser->getEmail(),
                'fname'    => $updatedUser->getFname(),
                'lname'    => $updatedUser->getLname(),
                'password' => $updatedUser->getPassword(),
                'defaultCurrency' => $updatedUser->getDefaultCurrency()
            ];
        } else {
            $updated[] = [
                'id'       => $user->getId(),
                'email'    => $user->getEmail(),
                'fname'    => $user->getFname(),
                'lname'    => $user->getLname(),
                'password' => $user->getPassword(),
                'defaultCurrency' => $user->getDefaultCurrency()
            ];
        }
    }
    file_put_contents(BASE_PATH . "/data/json/users.json", json_encode($updated, JSON_PRETTY_PRINT));
}

function jsonDeleteUser($email)
{
    $users = jsonLoadAllUsers();
    $remaining = [];

    foreach ($users as $user) {
        if ($user->getEmail() !== $email) {
            $remaining[] = [
                'id'       => $user->getId(),
                'email'    => $user->getEmail(),
                'fname'    => $user->getFname(),
                'lname'    => $user->getLname(),
                'password' => $user->getPassword(),
                'defaultCurrency' => $user->getDefaultCurrency()
            ];
        }
    }
    file_put_contents(BASE_PATH . "/data/json/users.json", json_encode($remaining, JSON_PRETTY_PRINT));
}

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

function jsonLoadOneTransaction($pid) : BLLTransaction
{
    $transaction = new BLLTransaction();
    $transaction->fromArray(jsonOne(BASE_PATH . "/data/json/transactions.json",$pid));
    return $transaction;
}

function jsonLoadOneTransactionForUser($pid,$puser) : BLLTransaction
{
    $transaction = jsonLoadOneTransaction($pid);
    if($transaction->user === $puser)
        return $transaction;
    else
        return null;
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
        $transaction = new BLLTransaction();
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

function getNextCategoryId()
{
    $categories = jsonLoadAllCategories();
    if (empty($categories)) return 1;
    $max = 0;
    foreach ($categories as $c) {
        if ($c->getId() > $max) $max = $c->getId();
    }
    return $max + 1;
}

function jsonLoadAllCategories()
{
    $file_path = BASE_PATH . '/data/json/categories.json';    
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
    $categories_data = json_decode($json_data, true); // true = associative arrays
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($categories_data)) {
        return [];
    }
    $categories = [];
    foreach ($categories_data as $category_data) {
        $category = new BLLCategory();
        $category->setId($category_data['id'] ?? null);
        $category->setUserId($category_data['userId'] ?? null);
        $category->setName($category_data['name'] ?? null);
        $categories[] = $category;
    }
    return $categories;
}


function jsonLoadAllCategoriesForUser($puser) : array
{
    $categories = jsonLoadAllCategories();
    return array_filter($categories, function($c) use ($puser) {
        return $c->getUserId() === $puser;
    });
}

function jsonSaveCategory($category)
{
    $file_path = BASE_PATH . "/data/json/categories.json";
    $json_data = file_exists($file_path) ? file_get_contents($file_path) : '[]';
    $categories = json_decode($json_data, true);
    if (!is_array($categories)) $categories = [];

    $categories[] = [
        'id'           => $category->getId(),
        'userId'       => $category->getUserId(),
        'name'         => $category->getName()
    ];

    file_put_contents($file_path, json_encode($categories, JSON_PRETTY_PRINT));
}

function jsonDeleteCategory($categoryId)
{
    $file_path = BASE_PATH . "/data/json/categories.json";
    $json_data = file_exists($file_path) ? file_get_contents($file_path) : '[]';
    $categories = json_decode($json_data, true);
    if (!is_array($categories)) $categories = [];

    $categories = array_filter($categories, function($c) use ($categoryId) {
        return $c['id'] !== $categoryId;
    });

    file_put_contents($file_path, json_encode(array_values($categories), JSON_PRETTY_PRINT));
}

function jsonLoadAllBoxInfo($location) : array
{
    $tjson = file_get_contents($location);
    return json_decode($tjson, true);
}

?>
