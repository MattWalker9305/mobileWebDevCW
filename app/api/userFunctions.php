<?php
require_once("classes.php");

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
        $user = new User();
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
?>