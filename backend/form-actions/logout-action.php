<?php
session_start();
session_destroy();

include __DIR__ . "/../settings/app-settings.php";

header("Location: " . BASE_URL . "/frontend/pages/home.php");
exit;