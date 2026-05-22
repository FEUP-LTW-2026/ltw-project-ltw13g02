<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../../utils/session.php');

    $session = new Session();
    
    // Verify if the form fields are empty
    if (empty($_POST['email-username']) || empty($_POST['password'])) {
        $session->addErrorMessage('All fields are mandatory.');
        header('Location: ' . $_SERVER['HTTP_REFERER']); // HTTP redirect to the previous page
        exit;
    }

    require_once(__DIR__ . '/../../database/connection.db.php');
    require_once(__DIR__ . '/../../database/authenticated_user.class.php');

    $db = getDatabaseConnection();

    try {
        $user = AuthenticatedUser::getUserWithPassword($db, $_POST['email-username'], $_POST['password']);

        if ($user) {
            $session->setId($user->getId());
            $session->setUsername($user->getUsername());
            $session->setEmail($user->getEmail());
            $session->setRole($user->getRole());
            $session->setIsActive($user->IsActive());
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
        $session->addErrorMessage('Database error occured.');
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']); // HTTP redirect to the previous page
?>  