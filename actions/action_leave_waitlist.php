<?php
    declare(strict_types=1);

    // TODO: require_once in the beggining of the file or in the middle?
    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/gymClass.class.php');
    require_once(__DIR__ . '/../database/enrollment.class.php');

    $session = new Session();

    if (!$session->isLoggedIn()) {
        header('Location: ../pages/login.php');
        exit;
    }

    // TODO: this function is needed in almost every action file (create a helper function?)
    function redirectToPreviousPage() {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Validate input
    $classId = $_POST['class_id'] ?? null;
    if (empty($classId)) {
        $session->addErrorMessage('Class ID is required.');
        redirectToPreviousPage();
    }

    if ($_POST['csrf_token'] !== $session->getCSRFToken()) {
        $session->addErrorMessage('Invalid CSRF token.');
        redirectToPreviousPage();
    }

    $classId = (int)$classId;
    $memberId = $session->getId();
    $db = getDatabaseConnection();

    // Fetch class and its type
    $class = GymClass::getClassById($db, $classId);
    if ($class === null) {
        $session->addErrorMessage('Class not found.');
        redirectToPreviousPage();
    }

    try { // TODO: use try catch blocks in all actions main database operation in other actions
        Enrollment::leaveWaitlist($db, $memberId, $classId);
        $session->addSuccessMessage('Successfully left the waitlist.');
    } catch (PDOException $e) { // TODO: normalise error messages across the actions
        error_log($e->getMessage());
        $session->addErrorMessage('Failed to leave waitlist: ' . $e->getMessage());
    }

    redirectToPreviousPage();
?>