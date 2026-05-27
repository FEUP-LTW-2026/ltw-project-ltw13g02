<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/gymClass.class.php');
    require_once(__DIR__ . '/../utils/helpers.php');

    $session = new Session();

    require_once(__DIR__ . '/../utils/role_change_guard.php');

    if (!$session->isLoggedIn()) {
        redirectToLogin();
    }

    if(empty($_GET['class_id'])) {
        $session->addErrorMessage('Class ID is required.');
        redirectToPreviousPage();
    }

    $classId = (int)$_GET['class_id'];
    if ($classId <= 0) {
        $session->addErrorMessage('Invalid class ID.');
        redirectToPreviousPage();
    }

    $db = getDatabaseConnection();

    $enrolledMembers = GymClass::getEnrolledMembers($db, $classId);

    foreach ($enrolledMembers as &$member) {
        $member['photo_url'] = getProfileImageUrl($member['id']);
    }

    unset($member); // Break the reference

    // Return data as JSON
    echo json_encode($enrolledMembers);
?>