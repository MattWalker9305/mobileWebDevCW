<?php
require_once ("classes.php");

function renderLoginForm()
{
    $html = <<<FORM
    <div class="form-wrapper">
        <form method="post" action="login.php">
            <div class="row mb-3">
                <label for="myuser" class="col-sm-2 col-form-label">Email:</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="myuser" name="myuser" placeholder="Enter email" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="mypassword" class="col-sm-2 col-form-label">Password:</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="mypassword" name="mypassword" placeholder="Password" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </div>
        </form>
    </div>
FORM;
    return $html;
}

function renderRegisterForm()
{
    $html = <<<FORM
    <div class="form-wrapper">
        <form method="post" action="register.php" accept-charset="utf-8">
            <div class="row mb-3">
                <label for="myuser" class="col-sm-2 col-form-label">Email:</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="myuser" name="myuser" placeholder="Enter email" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="myfname" class="col-sm-2 col-form-label">First Name:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="myfname" name="myfname" placeholder="Enter your first name" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="myname" class="col-sm-2 col-form-label">Last Name:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="mylname" name="mylname" placeholder="Enter your last name" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="mycurrency" class="col-sm-2 col-form-label">Currency:</label>
                <div class="col-sm-10">
                    <select class="form-select" id="mycurrency" name="mycurrency" required>
                        <option value="">Select Currency</option>
                        <option value="USD">USD</option>
                        <option value="EUR">EUR</option>
                        <option value="GBP">GBP</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="mypassword" class="col-sm-2 col-form-label">Password:</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="mypassword" name="mypassword" placeholder="Enter password" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="confirm_password" class="col-sm-2 col-form-label">Confirm Password:</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </div>
        </form>
    </div>
FORM;
    return $html;
}

function renderUpdateForm($userInfo)
{
    $defaultCurrency = $userInfo->getDefaultCurrency();
    $usdSelected = ($defaultCurrency == 'USD' ? 'selected' : '');
    $eurSelected = ($defaultCurrency == 'EUR' ? 'selected' : '');
    $gbpSelected = ($defaultCurrency == 'GBP' ? 'selected' : '');
    $html = <<<FORM
    <div class="form-group">
        <label>First Name: </label>
            <span class="profile-display" id="display-fname">{$userInfo->getFname()}</span>
            <input type="text" class="profile-input form-control" id="fname" value="{$userInfo->getFname()}" style="display:none;">
        </div>
        <div class="form-group">
            <label>Last Name: </label>
            <span class="profile-display" id="display-lname">{$userInfo->getLname()}</span>
            <input type="text" class="profile-input form-control" id="lname" value="{$userInfo->getLname()}" style="display:none;">
        </div>
        <div class="form-group">
            <label>Default Currency: </label>
            <span class="profile-display" id="display-defaultCurrency">{$userInfo->getDefaultCurrency()}</span>
            <select class="profile-input form-select" id="defaultCurrency" name="defaultCurrency" style="display:none;">
                <option value="USD" {$usdSelected}>USD</option>
                <option value="EUR" {$eurSelected}>EUR</option>
                <option value="GBP" {$gbpSelected}>GBP</option>
            </select>
        </div>
        <div class="form-group">
            <label>Email: </label>
            <span>{$userInfo->getEmail()}</span>
        </div>

        <button id="edit-profile" class="btn btn-secondary">Edit</button>
        <button id="save-profile" class="btn btn-primary" style="display:none;">Save Changes</button>
        <button id="cancel-edit" class="btn btn-outline-secondary" style="display:none;">Cancel</button>
        <div id="profile-message"></div>

        <hr>
        <form method="post" action="profile.php" onsubmit="return confirm('Are you sure you want to delete your account? This cannot be undone.');">
            <input type="hidden" name="action" value="delete">
            <button type="submit" class="btn btn-danger">Delete Account</button>
        </form>
    </div>
FORM;
    return $html;
}

function renderTransactionForm($defaultCurrency, $userId)
{

    $usdSelected = ($defaultCurrency == 'USD' ? 'selected' : '');
    $eurSelected = ($defaultCurrency == 'EUR' ? 'selected' : '');
    $gbpSelected = ($defaultCurrency == 'GBP' ? 'selected' : '');

    $categoryOptions = "";
    $categories = jsonLoadAllCategoriesForUser($userId);

    foreach ($categories as $category) {
        $categoryName = $category->getName();
        $categoryOptions .= "<option value='{$categoryName}'>{$categoryName}</option>";
    }

    $html = <<<FORM
    <div class="form-wrapper">
        <form method="post" action="new_transaction.php" accept-charset="utf-8">
        <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Type:</label>
                <div class="col-sm-10">
                    <div class="btn-group" data-bs-toggle="buttons">
                        <label class="btn btn-outline-secondary">
                            <input type="radio" name="mytype" value="income" required> Income
                        </label>
                        <label class="btn btn-outline-secondary">
                            <input type="radio" name="mytype" value="expense"> Expense
                        </label>
                    </div>
                </div>
            </div>
        <div class="row mb-3">
                <label for="myname" class="col-sm-2 col-form-label">Transaction Name:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="myname" name="myname" placeholder="Enter transaction name" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="mydate" class="col-sm-2 col-form-label">Date:</label>
                <div class="col-sm-10">
                    <input type="date" class="form-control" id="mydate" name="mydate" placeholder="Enter date" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="myamount" class="col-sm-2 col-form-label">Amount:</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="myamount" name="myamount" placeholder="Enter amount" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="mycategory" class="col-sm-2 col-form-label">Category:</label>
                <div class="col-sm-10">
                    <select class="form-select" id="mycategory" name="mycategory" required>
                        <option value="">Select Category</option>
                        $categoryOptions
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="mycurrency" class="col-sm-2 col-form-label">Currency:</label>
                <div class="col-sm-10">
                    <select class="form-select" id="mycurrency" name="mycurrency" required>
                        <option value="">Select Currency</option>
                        <option value="USD" $usdSelected>USD</option>
                        <option value="EUR" $eurSelected>EUR</option>
                        <option value="GBP" $gbpSelected>GBP</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="mynotes" class="col-sm-2 col-form-label">Notes:</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="mynotes" name="mynotes" placeholder="Enter notes"></textarea>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary">Create Transaction</button>
                </div>
            </div>
        </form>
    </div>
FORM;
    return $html;
}

function renderTransactionEditForm($userId)
{
    $categoryOptions = "";
    $categories = jsonLoadAllCategoriesForUser($userId);

    foreach ($categories as $category) {
        $categoryName = $category->getName();
        $categoryOptions .= "<option value='{$categoryName}'>{$categoryName}</option>";
    }

    $html = <<<FORM
    <div class="modal-body">
        <input type="hidden" id="modal-id">
        <div class="row g-3">
            <div class="col-12">
                <label for="modal-name" class="form-label">Name</label>
                <input type="text" id="modal-name" class="form-control">
            </div>
            <div class="col-sm-6">
                <label for="modal-date" class="form-label">Date</label>
                <input type="date" id="modal-date" class="form-control">
            </div>
            <div class="col-sm-6">
                <label for="modal-type" class="form-label">Type</label>
                <select id="modal-type" class="form-select">
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>
            <div class="col-sm-6">
                <label for="modal-amount" class="form-label">Amount</label>
                <input type="number" id="modal-amount" class="form-control" step="0.01" min="0">
            </div>
            <div class="col-sm-6">
                <label for="modal-currency" class="form-label">Currency</label>
                <select class="form-select" id="modal-currency">
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                    <option value="GBP">GBP</option>
                </select>
            </div>
            <div class="col-12">
                <label for="modal-category" class="form-label">Category</label>
                <select id="modal-category" class="form-select">
                    $categoryOptions
                </select>
            </div>
            <div class="col-12">
                <label for="modal-notes" class="form-label">Notes</label>
                <textarea id="modal-notes" class="form-control" rows="2"></textarea>
            </div>
        </div>
    </div>
FORM;
    return $html;
}

function renderAddCategoryForm()
{
    $html = <<<FORM
    <form method="post" action="categories.php" accept-charset="utf-8">
        <div class="input-group" style="max-width: 480px;">
            <input type="text" class="form-control" id="mycategory" name="mycategory" placeholder="Enter category name" required>
            <button type="submit" class="btn btn-primary">+ Add</button>
        </div>
    </form>
FORM;
    return $html;
}
?>
