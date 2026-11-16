<?php

function saveOrganization($connection, $organization) {
    $sql = "INSERT INTO organizations (
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
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $statement = $connection->prepare($sql);

    $statement->bind_param(
        "ssssssssssss",
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