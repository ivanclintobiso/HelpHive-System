<?php

function getTestSavedIds() {
    if (!isset($_SESSION["test_saved_ids"])) {
        return [];
    }

    return $_SESSION["test_saved_ids"];
}


function getSavedOpportunityIds($connection, $userId) {
    if ($connection == null) {
        return getTestSavedIds();
    }

    $sql = "SELECT opportunity_id FROM saved_opportunities WHERE user_id = ?";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $userId);
    $statement->execute();

    $result = $statement->get_result();

    $savedIds = [];

    while ($row = $result->fetch_assoc()) {
        $savedIds[] = (int) $row["opportunity_id"];
    }

    return $savedIds;
}


function isOpportunitySaved($connection, $userId, $opportunityId) {
    if ($connection == null) {
        return in_array($opportunityId, getTestSavedIds());
    }

    $sql = "SELECT id FROM saved_opportunities WHERE user_id = ? AND opportunity_id = ?";

    $statement = $connection->prepare($sql);
    $statement->bind_param("ii", $userId, $opportunityId);
    $statement->execute();

    $result = $statement->get_result();
    $row = $result->fetch_assoc();

    if ($row == null) {
        return false;
    }

    return true;
}


function saveOpportunity($connection, $userId, $opportunityId) {
    if ($connection == null) {
        $savedIds = getTestSavedIds();
        $savedIds[] = $opportunityId;
        $_SESSION["test_saved_ids"] = $savedIds;

        return true;
    }

    $sql = "INSERT INTO saved_opportunities (user_id, opportunity_id) VALUES (?, ?)";

    $statement = $connection->prepare($sql);
    $statement->bind_param("ii", $userId, $opportunityId);

    return $statement->execute();
}


function removeSavedOpportunity($connection, $userId, $opportunityId) {
    if ($connection == null) {
        $savedIds = getTestSavedIds();
        $_SESSION["test_saved_ids"] = array_values(array_diff($savedIds, [$opportunityId]));

        return true;
    }

    $sql = "DELETE FROM saved_opportunities WHERE user_id = ? AND opportunity_id = ?";

    $statement = $connection->prepare($sql);
    $statement->bind_param("ii", $userId, $opportunityId);

    return $statement->execute();
}