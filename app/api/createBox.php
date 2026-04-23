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
                    $id = null,
                    $deletable = FALSE,
                    $deleteAction = "")
{
    $deleteBtn = "";
    if ($deletable && $deleteAction && $id !== null) {
        $safeName = htmlspecialchars($title, ENT_QUOTES);
        $deleteBtn = <<<BTN
<form method="POST" action="{$deleteAction}" onsubmit="return confirm('Delete &quot;{$safeName}&quot;?')" class="mb-0">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="category_id" value="{$id}">
    <button type="submit" class="btn-close" aria-label="Delete"></button>
</form>
BTN;
    }

    if ($clickable) {
        $tbox = <<<BOX
        <div class="box transaction-box clickable"
             data-id="{$id}"
             data-name="{$title}"
             data-date="{$date}"
             data-type="{$type}"
             data-category="{$categoryName}"
             data-currency="{$currency}"
             data-amount="{$amount}"
             data-notes="{$notes}">
            <h2>{$title}</h2>
            <p>{$date} {$type} {$categoryName} {$currency} {$amount}</p>
        </div>
BOX;
    } elseif ($deletable) {
        $tbox = <<<BOX
        <div class="box d-flex justify-content-between align-items-center">
            <span>{$title}</span>
            {$deleteBtn}
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