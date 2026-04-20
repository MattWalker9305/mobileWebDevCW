<?php
require_once ("oo_bll.inc.php");
// ----------SPENDING OVERVIEW RENDERING----------------------------------------------

function renderSpendingOverview(){
    
}

// ----------SMARTPHONE RENDERING----------------------------------------------
function renderSmartphoneTable(array $smartphonelist)
{
    usort($smartphonelist, function ($a, $b) {
        return $b->score <=> $a->score;
    });

    $trowdata = "";
    foreach ($smartphonelist as $tc) {
        $tlink = "<a class=\"btn btn-info\" href=\"phones.php?type=smartphone&id={$tc->id}\">More...</a>";
        $trowdata .= "<tr><td>{$tc->score}</td><td>{$tc->make} {$tc->model}</td><td>{$tc->price}</td><td>{$tc->release}</td><td>{$tc->screen_size}</td><td>{$tlink}</td></tr>";
    }
    $ttable = <<<TABLE
<table class="table table-striped table-hover">
	<thead>
		<tr>
            <th>Score</th>
	       	<th>Make</th>
			<th>Price</th>
            <th>Release</th>
            <th>Screen size</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	   {$trowdata}
	</tbody>
</table>
TABLE;
	   return $ttable;
}

function renderSmartphoneOverview(BLLSmartphone $pc)
{
    $tbio = !empty($pc->desc_href) ? file_get_contents("data/html/smartphone/{$pc->desc_href}") : "There are no details on this smartphone.";
    $timgref = "img/smartphone/{$pc->model}.jpg";
    $timg = $timgref;
    $toverview = <<<OV
    <article class="row marketing">
        <h2>Smartphone Details</h2>
        <div class="media-left">
            <img src="$timg" width="256" />
        </div>
        <div class="media-body">
            <div class="well">
                <div class="media-right" style="float: right">
                    <h1><strong>Score {$pc->score}</strong></h1>
                </div>
                <h1>{$pc->make} {$pc->model}</h1>
                <h3>Price: <strong>{$pc->price}</strong></h3>
                <h4>Screen Size: {$pc->screen_size} 
                <br>Dimensions: {$pc->dimensions}
                <br>Weight: {$pc->weight}
                <br>Release: {$pc->release}
                <br>OS: {$pc->os}</h4>
                <d  iv class="col">
                    {$tbio}
                </div>
            </div>
        </div>
    </article>
OV;
    return $toverview;
}

// ----------LOGIN RENDERING----------------------------------------------
function renderLoginForm()
{
    $html = <<<FORM
    <div class="form-wrapper">
        <form method="post" action="login.php" class="form-horizontal">
            <div class="form-group">
                <label for="myuser" class="col-sm-2 control-label">Email:</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="myuser" name="myuser" placeholder="Enter email" required>
                </div>
            </div>
            <div class="form-group">
                <label for="mypassword" class="col-sm-2 control-label">Password:</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="mypassword" name="mypassword" placeholder="Password" required>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </div>
        </form>
    </div>
FORM;
    return $html;
}


// ----------REGISTER RENDERING----------------------------------------------
function renderRegisterForm()
{
    $html = <<<FORM
    <div class="form-wrapper">
        <form method="post" action="register.php" class="form-horizontal" accept-charset="utf-8">
            <div class="form-group">
                <label for="myuser" class="col-sm-2 control-label">Email:</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="myuser" name="myuser" placeholder="Enter email" required>
                </div>
            </div>
            <div class="form-group">
                <label for="myfname" class="col-sm-2 control-label">First Name:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="myfname" name="myfname" placeholder="Enter your first name" required>
                </div>
            </div>
            <div class="form-group">
                <label for="myname" class="col-sm-2 control-label">Last Name:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="mylname" name="mylname" placeholder="Enter your last name" required>
                </div>
            </div>
            <div class="form-group">
                <label for="myname" class="col-sm-2 control-label">Username:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="myusername" name="myusername" placeholder="Enter your username" required>
                </div>
            </div>
            <div class="form-group">
                <label for="mypassword" class="col-sm-2 control-label">Password:</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="mypassword" name="mypassword" placeholder="Enter password" required>
                </div>
            </div>
            <div class="form-group">
                <label for="confirm_password" class="col-sm-2 control-label">Confirm Password:</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </div>
        </form>
    </div>
FORM;
    return $html;
}

// ----------TRANSACTION RENDERING----------------------------------------------

function renderTransactionForm()
{
    $html = <<<FORM
    <div class="form-wrapper">
        <form method="post" action="register.php" class="form-horizontal" accept-charset="utf-8">
        <div class="form-group">
                <label class="col-sm-2 control-label">Type:</label>
                <div class="col-sm-10">
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default">
                            <input type="radio" name="mytype" value="income" required> Income
                        </label>
                        <label class="btn btn-default">
                            <input type="radio" name="mytype" value="expense"> Expense
                        </label>
                    </div>
                </div>
            </div>
        <div class="form-group">
                <label for="myname" class="col-sm-2 control-label">Transaction Name:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="myname" name="myname" placeholder="Enter transaction name" required>
                </div>
            </div>
            <div class="form-group">
                <label for="mydate" class="col-sm-2 control-label">Date:</label>
                <div class="col-sm-10">
                    <input type="date" class="form-control" id="mydate" name="mydate" placeholder="Enter date" required>
                </div>
            </div>
            <div class="form-group">
                <label for="myamount" class="col-sm-2 control-label">Amount:</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="myamount" name="myamount" placeholder="Enter amount" required>
                </div>
            </div>
            <div class="form-group">
                <label for="mycategory" class="col-sm-2 control-label">Category:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="mycategory" name="mycategory" placeholder="Enter category" required>
                </div>
            </div>
            <div class="form-group">
                <label for="mycurrency" class="col-sm-2 control-label">Currency:</label>
                <div class="col-sm-10">
                    <select class="form-control" id="mycurrency" name="mycurrency" required>
                        <option value="">Select Currency</option>
                        <option value="USD">USD</option>
                        <option value="EUR">EUR</option>
                        <option value="GBP">GBP</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="mynotes" class="col-sm-2 control-label">Notes:</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="mynotes" name="mynotes" placeholder="Enter notes" required></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </div>
        </form>
    </div>
FORM;
    return $html;
}
// ----------BOX RENDERING----------------------------------------------
function renderBox($title, $content)
{
    $tbox = <<<BOX
    <div class="box">
        <h2>{$title}</h2>
        <p>{$content}</p>
    </div>
BOX;
    return $tbox;
}
?>