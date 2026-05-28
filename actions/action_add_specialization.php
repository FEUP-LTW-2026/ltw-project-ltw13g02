<?php
    // TODO: REMOVE
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

    if(empty($_POST['specialization_id'])) {
        $session->addErrorMessage('Specialization is required.');
        redirectToPreviousPage();
    }

    if(!$session->verifyCsrfToken($_POST['csrf_token'])) {
        $session->addErrorMessage('Invalid CSRF token.');
        redirectToPreviousPage();
    }

    $specializationId = (int)$_POST['specialization_id'];

    if ($specializationId <= 0) {
        $session->addErrorMessage('Invalid specialization ID.');
        redirectToPreviousPage();
    }

    $db = getDatabaseConnection();

    try {
        Trainer::addSpecialization($db, $session->getId(), $specializationId);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        $session->addErrorMessage('Failed to add specialization: ' . $e->getMessage());
    }

    redirectToPreviousPage();
?>