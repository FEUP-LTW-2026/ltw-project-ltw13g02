<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../../utils/session.php');
    require_once(__DIR__ . '/../../database/connection.db.php');
    require_once(__DIR__ . '/../../database/authenticated_user.class.php');
    require_once(__DIR__ . '/../../utils/helpers.php');

    $session = new Session();
    
    // Verify if the form fields are empty
    if (empty($_POST['email-username']) || empty($_POST['password'])) {
        $session->addErrorMessage('All fields are mandatory.');
        redirectToPreviousPage();
    }

    $emailUsername = trim((string) $_POST['email-username']);
    $password = (string) $_POST['password'];

    $db = getDatabaseConnection();
    try {
        $user = AuthenticatedUser::getUserWithPassword($db, $emailUsername, $password);

        if ($user) {
            $roleChangeCount = AuthenticatedUser::getUserRoleChangeCount($db, $user->getId());
            $session->setRoleChangeCount($roleChangeCount);

            $session->setId($user->getId());
            $session->setUsername($user->getUsername());
            $session->setEmail($user->getEmail());
            $session->setRole($user->getRole());
            $session->setIsActive($user->IsActive());

            if(!$session->IsActive()) {
                $session->addInactiveAccountMessage();
                header('Location: /../../pages/index.php');
                exit;
            }

            // Ensure a CSRF token exists for the session
            $session->generateCSRFToken();

            $session->addSuccessMessage('Successfully logged in!');
            if (!$session->isActive()) {
                $session->addInactiveAccountMessage();
            }

            header('Location: /../../pages/index.php');
            exit;
        } else {
            $session->addErrorMessage('Wrong email or password.');
        }
    } catch (PDOException $e) {
        $session->addErrorMessage('Database error: ' . $e->getMessage());
    }
    redirectToPreviousPage();
?>