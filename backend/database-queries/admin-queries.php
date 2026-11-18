<?php

function makeAdminTestUsers() {
    $firstNames = ["Maria", "Juan", "Ana", "Pedro", "Liza", "Mark", "Grace", "Carlo", "Joy", "Ben", "Nina", "Paolo"];
    $lastNames = ["Santos", "Dela Cruz", "Reyes", "Garcia", "Ramos", "Mendoza", "Torres", "Flores", "Cruz", "Bautista", "Aquino", "Villanueva"];

    $users = [];

    for ($i = 0; $i < 12; $i++) {
        $status = "active";

        if ($i == 4) {
            $status = "banned";
        }

        $users[] = [
            "id" => $i + 1,
            "first_name" => $firstNames[$i],
            "last_name" => $lastNames[$i],
            "email" => strtolower($firstNames[$i]) . "@example.com",
            "role" => "volunteer",
            "status" => $status,
            "ban_reason" => "",
            "profile_image" => "profile" . (($i % 5) + 1) . "-defaultrandomprofile.png",
            "created_at" => date("Y-m-d H:i:s", strtotime("-" . ($i + 1) . " days"))
        ];
    }

    $users[] = [
        "id" => TEST_ADMIN_ID,
        "first_name" => "Test",
        "last_name" => "Admin",
        "email" => TEST_ADMIN_EMAIL,
        "role" => "admin",
        "status" => "active",
        "ban_reason" => "",
        "profile_image" => "profile1-defaultrandomprofile.png",
        "created_at" => date("Y-m-d H:i:s", strtotime("-30 days"))
    ];

    return array_reverse($users);
}


function makeAdminTestOrganization($id, $ownerId, $ownerName, $name, $type, $city, $status, $daysAgo) {
    return [
        "id" => $id,
        "user_id" => $ownerId,
        "owner_name" => $ownerName,
        "organization_name" => $name,
        "organization_type" => $type,
        "about" => "Sample organization used to test the admin dashboard.",
        "cause_areas" => "environment,community",
        "city_province" => $city,
        "email" => "contact@" . strtolower(str_replace(" ", "", $name)) . ".org",
        "contact_name" => $ownerName,
        "position_role" => "Volunteer Coordinator",
        "logo" => "",
        "contact_number" => "09XX XXX XXXX",
        "website" => "www." . strtolower(str_replace(" ", "", $name)) . ".org",
        "status" => $status,
        "rejection_reason" => "",
        "created_at" => date("Y-m-d H:i:s", strtotime("-" . $daysAgo . " days"))
    ];
}

function makeAdminTestOrganizations() {
    return [
        makeAdminTestOrganization(5, 5, "Liza Ramos", "Bayanihan Youth Hub", "Community Group", "Davao City, Davao del Sur", "pending", 1),
        makeAdminTestOrganization(4, 4, "Pedro Garcia", "Kids First Learning Center", "School / Educational Institution", "Cebu City, Cebu", "pending", 2),
        makeAdminTestOrganization(3, 3, "Ana Reyes", "Paws and Hearts Shelter", "Nonprofit Organization", "Quezon City, Metro Manila", "pending", 3),
        makeAdminTestOrganization(2, 2, "Juan Dela Cruz", "Clean Coast Alliance", "Nonprofit Organization", "Iloilo City, Iloilo", "approved", 10),
        makeAdminTestOrganization(1, 1, "Maria Santos", "Green Future Initiative", "Nonprofit Organization", "Cebu City, Cebu", "approved", 20)
    ];
}


function makeAdminTestOpportunity($id, $organizationName, $ownerId, $title, $status, $daysAgo) {
    return [
        "id" => $id,
        "title" => $title,
        "short_description" => "Sample opportunity used to test the admin dashboard.",
        "about" => "Volunteers will help with this activity.",
        "location" => "Cebu City",
        "activity_date" => date("Y-m-d", strtotime("+" . ($id + 5) . " days")),
        "status" => $status,
        "rejection_reason" => "",
        "created_at" => date("Y-m-d H:i:s", strtotime("-" . $daysAgo . " days")),
        "organization_name" => $organizationName,
        "owner_id" => $ownerId,
        "image" => "",
        "cause" => "environment",
        "what_youll_do" => "Prepare the activity area.\nHelp the team with the main tasks.\nClean and organize the area after the activity.",
        "location_type" => "On-site",
        "start_time" => "08:00:00",
        "end_time" => "12:00:00",
        "volunteers_needed" => 20,
        "good_for" => "beginners,groups",
        "duration" => "one-day",
        "skills" => "No experience required"
    ];
}

function makeAdminTestOpportunities() {
    return [
        makeAdminTestOpportunity(6, "Clean Coast Alliance", 2, "Beach Cleanup Drive", "pending_review", 1),
        makeAdminTestOpportunity(5, "Green Future Initiative", 1, "Tree Planting Weekend", "pending_review", 2),
        makeAdminTestOpportunity(4, "Green Future Initiative", 1, "Community Garden Day", "published", 5),
        makeAdminTestOpportunity(3, "Clean Coast Alliance", 2, "River Cleanup", "published", 8),
        makeAdminTestOpportunity(2, "Green Future Initiative", 1, "Recycling Workshop", "published", 12),
        makeAdminTestOpportunity(1, "Clean Coast Alliance", 2, "Old Sample Activity", "removed", 20)
    ];
}


function makeAdminTestMessage($id, $name, $subject, $message, $isRead, $daysAgo) {
    return [
        "id" => $id,
        "full_name" => $name,
        "email" => strtolower(str_replace(" ", ".", $name)) . "@example.com",
        "subject" => $subject,
        "message" => $message,
        "is_read" => $isRead,
        "profile_image" => "profile" . (($id % 5) + 1) . "-defaultrandomprofile.png",
        "created_at" => date("Y-m-d H:i:s", strtotime("-" . $daysAgo . " days"))
    ];
}

function makeAdminTestMessages() {
    return [
        makeAdminTestMessage(5, "Rica Mendez", "Question about volunteering", "Hi, can students volunteer even if they are under 18?", 0, 0),
        makeAdminTestMessage(4, "Leo Navarro", "Report a problem", "The search page is slow on my phone. Please check.", 0, 1),
        makeAdminTestMessage(3, "Tina Lopez", "Partnership", "Our company would like to partner with HelpHive for events.", 0, 2),
        makeAdminTestMessage(2, "Carl Uy", "Thank you", "Thank you for creating this platform. It helped our group a lot.", 1, 6),
        makeAdminTestMessage(1, "Bea Castro", "Account help", "I cannot change my profile picture.", 1, 9)
    ];
}


function getTestAdminData() {
    if (!isset($_SESSION["test_admin_data"])) {
        $_SESSION["test_admin_data"] = [
            "users" => makeAdminTestUsers(),
            "organizations" => makeAdminTestOrganizations(),
            "opportunities" => makeAdminTestOpportunities(),
            "messages" => makeAdminTestMessages()
        ];
    }

    return $_SESSION["test_admin_data"];
}


function findRowById($rows, $id) {
    foreach ($rows as $row) {
        if ($row["id"] == $id) {
            return $row;
        }
    }

    return null;
}


function getAllUsers($connection) {
    if ($connection == null) {
        $data = getTestAdminData();
        return $data["users"];
    }

    $sql = "SELECT id, first_name, last_name, email, role, status, ban_reason, profile_image, created_at
            FROM users
            ORDER BY id DESC";

    $result = $connection->query($sql);

    $users = [];

    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    return $users;
}


function getOrganizationsForAdmin($connection) {
    if ($connection == null) {
        $data = getTestAdminData();
        return $data["organizations"];
    }

    $sql = "SELECT o.id, o.user_id, CONCAT(u.first_name, ' ', u.last_name) AS owner_name,
                   o.organization_name, o.organization_type, o.about, o.cause_areas,
                   o.city_province, o.email, o.contact_name, o.position_role,
                   o.logo, o.contact_number, o.website,
                   o.status, o.rejection_reason, o.created_at
            FROM organizations o
            LEFT JOIN users u ON u.id = o.user_id
            ORDER BY o.id DESC";

    $result = $connection->query($sql);

    $organizations = [];

    while ($row = $result->fetch_assoc()) {
        $organizations[] = $row;
    }

    return $organizations;
}


function getOpportunitiesForAdmin($connection) {
    if ($connection == null) {
        $data = getTestAdminData();
        return $data["opportunities"];
    }

    $sql = "SELECT p.id, p.title, p.short_description, p.about, p.location, p.activity_date,
                   p.status, p.rejection_reason, p.created_at,
                   p.image, p.cause, p.what_youll_do, p.location_type,
                   p.start_time, p.end_time, p.volunteers_needed,
                   p.good_for, p.duration, p.skills,
                   o.organization_name, o.user_id AS owner_id
            FROM opportunities p
            LEFT JOIN organizations o ON o.id = p.organization_id
            WHERE p.status != 'draft'
            ORDER BY p.id DESC";

    $result = $connection->query($sql);

    $opportunities = [];

    while ($row = $result->fetch_assoc()) {
        $opportunities[] = $row;
    }

    return $opportunities;
}


function getMessagesForAdmin($connection) {
    if ($connection == null) {
        $data = getTestAdminData();
        return $data["messages"];
    }

    $sql = "SELECT c.id, c.full_name, c.email, c.subject, c.message, c.is_read, c.created_at,
                   IFNULL(u.profile_image, 'profile1-defaultrandomprofile.png') AS profile_image
            FROM contact_messages c
            LEFT JOIN users u ON u.email = c.email
            ORDER BY c.id DESC";

    $result = $connection->query($sql);

    $messages = [];

    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    return $messages;
}


function getAdminCounts($connection) {
    $organizations = getOrganizationsForAdmin($connection);
    $opportunities = getOpportunitiesForAdmin($connection);
    $messages = getMessagesForAdmin($connection);

    $counts = [
        "pending_organizations" => 0,
        "pending_opportunities" => 0,
        "unread_messages" => 0,
        "total" => 0
    ];

    foreach ($organizations as $organization) {
        if ($organization["status"] == "pending") {
            $counts["pending_organizations"] = $counts["pending_organizations"] + 1;
        }
    }

    foreach ($opportunities as $opportunity) {
        if ($opportunity["status"] == "pending_review") {
            $counts["pending_opportunities"] = $counts["pending_opportunities"] + 1;
        }
    }

    foreach ($messages as $message) {
        if ($message["is_read"] == 0) {
            $counts["unread_messages"] = $counts["unread_messages"] + 1;
        }
    }

    $counts["total"] = $counts["pending_organizations"] + $counts["pending_opportunities"] + $counts["unread_messages"];

    return $counts;
}


function updateUserStatus($connection, $userId, $status, $reason) {
    if ($connection == null) {
        $data = getTestAdminData();

        for ($i = 0; $i < count($data["users"]); $i++) {
            if ($data["users"][$i]["id"] == $userId) {
                $data["users"][$i]["status"] = $status;
                $data["users"][$i]["ban_reason"] = $reason;
            }
        }

        $_SESSION["test_admin_data"] = $data;

        return true;
    }

    $sql = "UPDATE users SET status = ?, ban_reason = ? WHERE id = ?";

    $statement = $connection->prepare($sql);
    $statement->bind_param("ssi", $status, $reason, $userId);

    return $statement->execute();
}


function updateOrganizationStatus($connection, $organizationId, $status, $reason) {
    if ($connection == null) {
        $data = getTestAdminData();

        for ($i = 0; $i < count($data["organizations"]); $i++) {
            if ($data["organizations"][$i]["id"] == $organizationId) {
                $data["organizations"][$i]["status"] = $status;
                $data["organizations"][$i]["rejection_reason"] = $reason;
            }
        }

        $_SESSION["test_admin_data"] = $data;

        return true;
    }

    $sql = "UPDATE organizations SET status = ?, rejection_reason = ? WHERE id = ?";

    $statement = $connection->prepare($sql);
    $statement->bind_param("ssi", $status, $reason, $organizationId);

    return $statement->execute();
}


function updateOpportunityStatus($connection, $opportunityId, $status, $reason) {
    if ($connection == null) {
        $data = getTestAdminData();

        for ($i = 0; $i < count($data["opportunities"]); $i++) {
            if ($data["opportunities"][$i]["id"] == $opportunityId) {
                $data["opportunities"][$i]["status"] = $status;
                $data["opportunities"][$i]["rejection_reason"] = $reason;
            }
        }

        $_SESSION["test_admin_data"] = $data;

        return true;
    }

    $sql = "UPDATE opportunities SET status = ?, rejection_reason = ? WHERE id = ?";

    $statement = $connection->prepare($sql);
    $statement->bind_param("ssi", $status, $reason, $opportunityId);

    return $statement->execute();
}


function updateMessageRead($connection, $messageId, $isRead) {
    if ($connection == null) {
        $data = getTestAdminData();

        for ($i = 0; $i < count($data["messages"]); $i++) {
            if ($data["messages"][$i]["id"] == $messageId) {
                $data["messages"][$i]["is_read"] = $isRead;
            }
        }

        $_SESSION["test_admin_data"] = $data;

        return true;
    }

    $sql = "UPDATE contact_messages SET is_read = ? WHERE id = ?";

    $statement = $connection->prepare($sql);
    $statement->bind_param("ii", $isRead, $messageId);

    return $statement->execute();
}