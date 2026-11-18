<?php
if (!isset($searchText)) {
    $searchText = "";
}

if (!isset($searchPlaceholder)) {
    $searchPlaceholder = "Search";
}

if (!isset($filterOptions)) {
    $filterOptions = [];
}

if (!isset($filterValue)) {
    $filterValue = "all";
}
?>
<form method="get" action="<?php echo basename($_SERVER["PHP_SELF"]); ?>" class="admin-toolbar">

    <div class="search-box">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text"
               name="search"
               value="<?php echo safe($searchText); ?>"
               placeholder="<?php echo safe($searchPlaceholder); ?>">
    </div>

    <?php if (count($filterOptions) > 0) { ?>
        <select name="filter" class="filter-select" onchange="this.form.submit()">
            <?php foreach ($filterOptions as $value => $label) { ?>
                <option value="<?php echo $value; ?>"
                        <?php if ($filterValue == $value) { echo "selected"; } ?>>
                    <?php echo $label; ?>
                </option>
            <?php } ?>
        </select>
    <?php } ?>

</form>