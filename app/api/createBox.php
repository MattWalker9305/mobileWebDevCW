<?php
require_once ("classes.php");

function renderBox($title, 
                    $content = "",
                    $date = "", 
                    $type = "", 
                    $categoryName = "", 
                    $currency = "",
                    $amount = "",  
                    $notes = "", 
                    $clickable = FALSE, 
                    $id = null)
{
    if ($clickable) {
        $tbox = <<<BOX
        <div class="box clickable" >
            <h2>{$title}</h2>
            <p>{$content} {$date} {$type} {$categoryName} {$currency} {$amount}</p>
        </div>
BOX;
    } else {
        $tbox = <<<BOX
        <div class="box">
            <h2>{$title}</h2>
            <p>{$content} {$date} {$type} {$categoryName} {$currency} {$amount}</p>
        </div> 
BOX;
    }
    return $tbox;
}

function renderSummaryBox($title, $value)
{
    $tbox = <<<BOX
    <div class="box summary-box">
        <h2>{$title}</h2>
        <h3 class="summary-value">{$value}</h3>
    </div>
BOX;
    return $tbox;
}
?>