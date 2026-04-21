<?php 
//----INCLUDE APIS------------------------------------
include("api/api.inc.php");

//----PAGE GENERATION LOGIC---------------------------

function createPage()
{
    $loggedInUser = jsonLoadOneUser($_SESSION['myuser']);
    $userID = $loggedInUser->getId();

    $userCategories = jsonLoadAllCategoriesForUser($userID ?? "");

    $categorieBoxes = "";
    foreach ($userCategories as $box) {
        $categorieBoxes .= '<div class="col-12 col-md-6 col-lg-4">';
        $categorieBoxes .= renderBox($box->getName());
        $categorieBoxes .= '</div>';
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $category = $_POST["mycategory"] ?? "";
        if (!empty($category)) {
            $new_category = new BLLCategory();
            $new_category->setId(getNextCategoryId());
            $new_category->setUserId($userID);
            $new_category->setName($category);
            jsonSaveCategory($new_category);
            header("Location: categories.php");
            exit();
        }
    }

    $addCategoryForm = renderAddCategoryForm();

    //Construct the Page
$tcontent = <<<PAGE
<section class = "row details">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">Categories</h3>
        </div>
        <div class="panel-body">
            <div class="category-content">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    {$categorieBoxes}
                </div>
            </div>
            <div class="category-actions">
                <h3 class="panel-title">Add Category</h3>
                <div class="add-category-form">
                    {$addCategoryForm}
                </div>
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

//Build up our Dynamic Content Items. 


if (!isset($_SESSION['myuser']))
{
    header("Location: login.php");
    die();
}
else{

    $tpagetitle = "Categories";
    $tpagelead  = "";
    $tpagecontent = createPage();
    $tpagefooter = "";

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
