<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../../utils/session.php');
    require_once(__DIR__ . '/../../utils/helpers.php');
    require_once(__DIR__ . '/../../database/membership.class.php');
    require_once(__DIR__ . '/../../database/connection.db.php');

    $session = new Session();

    require_once(__DIR__ . '/../../utils/protected_file_guard.php');

    if (!$session->isLoggedIn()) {
        redirectToLogin();
    }

    if (!$session->isMember()) {
        $session->addErrorMessage('Unauthorized action!');
        redirectToPreviousPage();
    }

    if(empty($_GET['page']) || !is_numeric($_GET['page']) || (int)$_GET['page'] <= 0) {
        $session->addErrorMessage('Invalid page number.');
        redirectToPreviousPage();
    }

    if (empty($_GET['per_page']) || !is_numeric($_GET['per_page']) || (int)$_GET['per_page'] <= 0) {
        $session->addErrorMessage('Invalid per page value.');
        redirectToPreviousPage();
    }

    $page = (int)$_GET['page'];
    $perPage = (int)$_GET['per_page'];

    $db = getDatabaseConnection();

    try {
        $pageMemberships = Membership::getMembershipPage($db, $session->getId(), $page, $perPage);
        echo json_encode($pageMemberships);
    } catch (PDOException $e) {
        $session->addErrorMessage('Database error: ' . $e->getMessage());
        redirectToPreviousPage();
    }
?>