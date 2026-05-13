<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');

    $session = new Session();

    $name = $_POST['first-name'] . ' ' . $_POST['last-name'];
    $email = $_POST['email'];
    
    if ($name && $email) {
        
        $session->setId(1);
        $session->setName($name);
        $session->setEmail($email);
        $session->addMessage('success', 'Successfully logged in!');

    } else { 

        $session->addMessage('error', 'All fields are mandatory.');
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']); // HTTP redirect to the previous page
?>