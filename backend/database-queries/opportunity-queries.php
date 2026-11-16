<?php

function getAllOpportunities() {
    $opportunities = [
        [
            "id" => 1,
            "title" => "Coastal Cleanup Drive",
            "organization" => "Blue Shores Initiative",
            "cause" => "environment",
            "location_type" => "On-site",
            "city" => "Lapu-Lapu City",
            "province" => "Cebu",
            "distance_km" => 12,
            "date_text" => "Sat, Oct 31, 2026 · 7:00–10:00 AM PHT (UTC+8)",
            "date" => "2026-10-31",
            "good_for" => "groups",
            "duration" => "one-day",
            "description" => "Collect litter along the coast to protect marine life and keep local beaches clean.",
            "skills" => ["Physical Work"],
            "image" => "temp-coastal-cleanup.jpg"
        ],
        [
            "id" => 2,
            "title" => "Animal Shelter Assistant",
            "organization" => "Paws & Care Foundation",
            "cause" => "animal",
            "location_type" => "On-site",
            "city" => "Cebu City",
            "province" => "Cebu",
            "distance_km" => 2,
            "date_text" => "Sat, Oct 24, 2026 · 8 AM–12 PM PHT (UTC+8)",
            "date" => "2026-10-24",
            "good_for" => "beginners",
            "duration" => "one-day",
            "description" => "Help care for rescued animals by preparing food, cleaning their spaces, and giving them attention.",
            "skills" => ["Animal Care", "Physical Work"],
            "image" => "temp-animal-shelter.jpg"
        ],
        [
            "id" => 3,
            "title" => "Community Care Companion",
            "organization" => "Kind Hearts Community",
            "cause" => "community",
            "location_type" => "On-site",
            "city" => "Mandaue City",
            "province" => "Cebu",
            "distance_km" => 6,
            "date_text" => "Sat, Nov 7, 2026 · 8 AM–12 PM PHT (UTC+8)",
            "date" => "2026-11-07",
            "good_for" => "beginners",
            "duration" => "one-day",
            "description" => "Support older adults through friendly conversations, simple activities, and community outreach.",
            "skills" => ["Communication"],
            "image" => "temp-community-care.jpg"
        ],
        [
            "id" => 4,
            "title" => "Weekend Reading Buddies",
            "organization" => "Bright Futures Foundation",
            "cause" => "education",
            "location_type" => "On-site",
            "city" => "Cebu City",
            "province" => "Cebu",
            "distance_km" => 3,
            "date_text" => "Every Sat, Oct 24, 2026 · 7:00–10:00 AM PHT (UTC+8)",
            "date" => "2026-10-24",
            "good_for" => "students",
            "duration" => "ongoing",
            "description" => "Help children build reading skills through fun learning activities and one-on-one tutoring.",
            "skills" => ["Teaching", "Communication"],
            "image" => "temp-reading-buddies.jpg"
        ],
        [
            "id" => 5,
            "title" => "Environmental Awareness Creator",
            "organization" => "Green Shift Media",
            "cause" => "environment",
            "location_type" => "Remote",
            "city" => "Lapu-Lapu City",
            "province" => "Cebu",
            "distance_km" => 12,
            "date_text" => "Sat, Oct 31, 2026 · 9 AM–12 PM PHT (UTC+8)",
            "date" => "2026-10-31",
            "good_for" => "individuals",
            "duration" => "one-day",
            "description" => "Create a digital infographic or awareness post about recycling, plastic pollution, or environmental conservation.",
            "skills" => ["Digital Design"],
            "image" => "temp-awareness-creator.jpg"
        ],
        [
            "id" => 6,
            "title" => "Tree Planting Day",
            "organization" => "Green Cebu Movement",
            "cause" => "environment",
            "location_type" => "On-site",
            "city" => "Talisay City",
            "province" => "Cebu",
            "distance_km" => 10,
            "date_text" => "Sat, Nov 14, 2026 · 6:00–10:00 AM PHT (UTC+8)",
            "date" => "2026-11-14",
            "good_for" => "groups",
            "duration" => "one-day",
            "description" => "Plant native tree seedlings in the hills and help bring back the forest cover.",
            "skills" => ["Physical Work"],
            "image" => "temp-tree-planting.jpg"
        ],
        [
            "id" => 7,
            "title" => "Food Pack Distribution",
            "organization" => "Share a Meal Cebu",
            "cause" => "community",
            "location_type" => "On-site",
            "city" => "Cebu City",
            "province" => "Cebu",
            "distance_km" => 1,
            "date_text" => "Sun, Nov 8, 2026 · 9 AM–1 PM PHT (UTC+8)",
            "date" => "2026-11-08",
            "good_for" => "groups",
            "duration" => "one-day",
            "description" => "Pack and hand out food boxes to families in need around the city.",
            "skills" => ["Physical Work", "Communication"],
            "image" => "temp-food-pack.jpg"
        ],
        [
            "id" => 8,
            "title" => "Online Math Tutor",
            "organization" => "Bright Futures Foundation",
            "cause" => "education",
            "location_type" => "Remote",
            "city" => "Cebu City",
            "province" => "Cebu",
            "distance_km" => 3,
            "date_text" => "Every Wed, Nov 4, 2026 · 6:00–7:30 PM PHT (UTC+8)",
            "date" => "2026-11-04",
            "good_for" => "students",
            "duration" => "ongoing",
            "description" => "Tutor grade school students in math through weekly online sessions.",
            "skills" => ["Teaching"],
            "image" => "temp-online-tutor.jpg"
        ],
        [
            "id" => 9,
            "title" => "Weekend Dog Walker",
            "organization" => "Paws & Care Foundation",
            "cause" => "animal",
            "location_type" => "On-site",
            "city" => "Mandaue City",
            "province" => "Cebu",
            "distance_km" => 6,
            "date_text" => "Sun, Nov 15, 2026 · 7–10 AM PHT (UTC+8)",
            "date" => "2026-11-15",
            "good_for" => "individuals",
            "duration" => "one-day",
            "description" => "Walk shelter dogs, give them exercise, and help them get used to people.",
            "skills" => ["Animal Care"],
            "image" => "temp-dog-walker.jpg"
        ],
        [
            "id" => 10,
            "title" => "Community Post Designer",
            "organization" => "Kind Hearts Community",
            "cause" => "community",
            "location_type" => "Remote",
            "city" => "Cebu City",
            "province" => "Cebu",
            "distance_km" => 2,
            "date_text" => "Sat, Nov 21, 2026 · 1–4 PM PHT (UTC+8)",
            "date" => "2026-11-21",
            "good_for" => "beginners",
            "duration" => "one-day",
            "description" => "Design simple posts that share community events and volunteer calls.",
            "skills" => ["Digital Design", "Communication"],
            "image" => "temp-post-designer.jpg"
        ]
    ];

    return $opportunities;
}


function searchOpportunities($filters) {
    $allOpportunities = getAllOpportunities();
    $results = [];

    foreach ($allOpportunities as $opportunity) {
        $isMatch = true;

        if ($filters["keyword"] != "") {
            $searchableText = $opportunity["title"] . " "
                . $opportunity["organization"] . " "
                . $opportunity["description"] . " "
                . implode(" ", $opportunity["skills"]);

            if (stripos($searchableText, $filters["keyword"]) === false) {
                $isMatch = false;
            }
        }

        if ($filters["location"] != "") {
            $locationParts = explode(",", $filters["location"]);
            $mainLocation = trim($locationParts[0]);
            $opportunityPlace = $opportunity["city"] . " " . $opportunity["province"] . " Philippines";

            if (stripos($opportunityPlace, $mainLocation) === false) {
                $isMatch = false;
            }
        }

        if ($filters["distance"] != "") {
            if ($opportunity["distance_km"] > $filters["distance"]) {
                $isMatch = false;
            }
        }

        if (count($filters["location_types"]) > 0) {
            if (!in_array($opportunity["location_type"], $filters["location_types"])) {
                $isMatch = false;
            }
        }

        if (count($filters["causes"]) > 0) {
            if (!in_array($opportunity["cause"], $filters["causes"])) {
                $isMatch = false;
            }
        }

        if (count($filters["good_for"]) > 0) {
            if (!in_array($opportunity["good_for"], $filters["good_for"])) {
                $isMatch = false;
            }
        }

        if (count($filters["durations"]) > 0) {
            if (!in_array($opportunity["duration"], $filters["durations"])) {
                $isMatch = false;
            }
        }

        if ($filters["from_date"] != "") {
            if ($opportunity["date"] < $filters["from_date"]) {
                $isMatch = false;
            }
        }

        if (count($filters["skills"]) > 0) {
            $hasSelectedSkill = false;

            foreach ($opportunity["skills"] as $skill) {
                if (in_array($skill, $filters["skills"])) {
                    $hasSelectedSkill = true;
                }
            }

            if (!$hasSelectedSkill) {
                $isMatch = false;
            }
        }

        if ($isMatch) {
            $results[] = $opportunity;
        }
    }

    return $results;
}