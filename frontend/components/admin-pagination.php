<?php
if (!isset($pageNumber)) {
    $pageNumber = 1;
}

if (!isset($totalPages)) {
    $totalPages = 1;
}

if (!isset($totalRows)) {
    $totalRows = 0;
}

if (!isset($searchText)) {
    $searchText = "";
}

if (!isset($filterValue)) {
    $filterValue = "all";
}
?>
<div class="pagination">

    <p class="pagination-text">
        Page <?php echo $pageNumber; ?> of <?php echo $totalPages; ?> (<?php echo $totalRows; ?> results)
    </p>

    <div class="pagination-buttons">

        <?php if ($pageNumber > 1) { ?>
            <a href="<?php echo safe(makeListLink($searchText, $filterValue, $pageNumber - 1)); ?>"
               class="action-button neutral">Previous</a>
        <?php } else { ?>
            <span class="action-button neutral disabled">Previous</span>
        <?php } ?>

        <?php if ($pageNumber < $totalPages) { ?>
            <a href="<?php echo safe(makeListLink($searchText, $filterValue, $pageNumber + 1)); ?>"
               class="action-button neutral">Next</a>
        <?php } else { ?>
            <span class="action-button neutral disabled">Next</span>
        <?php } ?>

    </div>

</div>