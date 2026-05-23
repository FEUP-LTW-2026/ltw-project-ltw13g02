<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/trainer.class.php');


    $session = new Session();

    if (!$session->isLoggedIn()) {
        header('Location: ../pages/login.php');
        exit;
    }

    function redirectToPreviousPage() {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    if (!$session->isTrainer()) {
        $session->addErrorMessage('Unauthorized action!');
        redirectToPreviousPage();
    }

    if($_POST['csrf_token'] !== $session->getCSRFToken()) {
        $session->addErrorMessage('Invalid CSRF token.');
        redirectToPreviousPage();
    }

    $specializationId = (int)$_POST['specialization_id'] ?? null;
    if (!$specializationId) {
        $session->addErrorMessage('Specialization ID is required.');
        redirectToPreviousPage();
    }

    $db = getDatabaseConnection();

    try {
        Trainer::addSpecialization($db, $session->getId(), $specializationId);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        $session->addErrorMessage('Failed to remove specialization: ' . $e->getMessage());
    }

    redirectToPreviousPage();
?>