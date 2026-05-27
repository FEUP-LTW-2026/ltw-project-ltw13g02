<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/trainer.class.php');
    require_once(__DIR__ . '/../utils/helpers.php');

    $session = new Session();

    require_once(__DIR__ . '/../utils/role_change_guard.php');

    if (!$session->isLoggedIn()) {
        redirectToLogin();
    }

    if (!$session->isTrainer()) {
        $session->addErrorMessage('Unauthorized action!');
        redirectToPreviousPage();
    }

    if(!$session->verifyCsrfToken($_POST['csrf_token'])) {
        $session->addErrorMessage('Invalid CSRF token.');
        redirectToPreviousPage();
    }

    if(empty($_POST['certification_id'])) {
        $session->addErrorMessage('Certification is required.');
        redirectToPreviousPage();
    }

    $certificationId = (int)$_POST['certification_id'];

    if ($certificationId <= 0) {
        $session->addErrorMessage('Invalid certification ID.');
        redirectToPreviousPage();
    }

    $db = getDatabaseConnection();

    try {
        Trainer::addCertification($db, $session->getId(), $certificationId);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        $session->addErrorMessage('Failed to add certification: ' . $e->getMessage());
    }
    redirectToPreviousPage();
?>