<?php

function saveOrganization($connection, $organization) {
    if ($connection == null) {
        $_SESSION["test_organization"] = [
            "id" => 1,
            "logo" => $organization["logo"],
            "organization_name" => $organization["organization_name"],
            "organization_type" => $organization["organization_type"],
            "about" => $organization["about"],
            "cause_areas" => $organization["cause_areas"],
            "city_province" => $organization["city_province"],
            "email" => $organization["email"],
            "status" => "approved",
            "rejection_reason" => ""
        ];

        return true;
    }

    $sql = "INSERT INTO organizations (
                user_id,
                logo,
                organization_name,
                organization_type,
                about,
                cause_areas,
                city_province,
                street_address,
                email,
                contact_number,
                website,
                contact_name,
                position_role
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $statement = $connection->prepare($sql);

    $statement->bind_param(
        "issssssssssss",
        $organization["user_id"],
        $organization["logo"],
        $organization["organization_name"],
        $organization["organization_type"],
        $organization["about"],
        $organization["cause_areas"],
        $organization["city_province"],
        $organization["street_address"],
        $organization["email"],
        $organization["contact_number"],
        $organization["website"],
        $organization["contact_name"],
        $organization["position_role"]
    );

    return $statement->execute();
}


function getTestOrganization() {
    if (isset($_SESSION["test_organization"])) {
        return $_SESSION["test_organization"];
    }

    return null;
}


function getMyOrganization($connection, $userId) {
    if ($connection == null) {
        return getTestOrganization();
    }

    $sql = "SELECT id, logo, organization_name, organization_type, about, cause_areas,
                   city_province, email, status, rejection_reason
            FROM organizations
            WHERE user_id = ?
            ORDER BY id DESC
            LIMIT 1";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $userId);
    $statement->execute();

    $result = $statement->get_result();

    return $result->fetch_assoc();
}