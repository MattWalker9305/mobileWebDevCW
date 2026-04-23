<?php
require_once("classes.php");

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
        $category = new Category();
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
        return $c['id'] != $categoryId;
    });

    file_put_contents($file_path, json_encode(array_values($categories), JSON_PRETTY_PRINT));
}
?>
